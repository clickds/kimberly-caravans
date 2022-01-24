<?php

namespace Tests\Feature\Admin\Manufacturers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->fakeStorage();
        $site = factory(Site::class)->create();
        $manufacturer = $this->createManufacturer();
        $data = $this->validData([
            'name' => $manufacturer->name,
            'site_ids' => [$site->id],
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $manufacturerData = Arr::except($data, ['site_ids', 'logo', 'motorhome_image', 'caravan_image']);
        $manufacturerData = array_merge($manufacturerData, ['id' => $manufacturer->id]);
        $this->assertDatabaseHas('manufacturers', $manufacturerData);
        $this->assertDatabaseHas('pageable_site', [
            'site_id' => $site->id,
            'pageable_id' => $manufacturer->id,
            'pageable_type' => Manufacturer::class,
        ]);
        $motorhomeMediaData = [
            'collection_name' => 'motorhomeImage',
            'model_id' => $manufacturer->id,
            'model_type' => Manufacturer::class,
        ];
        $this->assertDatabaseHas('media', $motorhomeMediaData);
        $this->assertFileExists($manufacturer->getFirstMedia('motorhomeImage')->getPath());
        $caravanMediaData = [
            'collection_name' => 'caravanImage',
            'model_id' => $manufacturer->id,
            'model_type' => Manufacturer::class,
        ];
        $this->assertDatabaseHas('media', $caravanMediaData);
        $this->assertFileExists($manufacturer->getFirstMedia('caravanImage')->getPath());
    }

    public function test_syncing_sites()
    {
        $old_site = $this->createSite();
        $new_site = $this->createSite();
        $manufacturer = $this->createManufacturer();
        $manufacturer->sites()->sync($old_site);
        $page = $this->createPageForPageable($manufacturer, $old_site);

        $data = $this->validData();
        $data['site_ids'] = [$new_site->id];

        $response = $this->submit($manufacturer, $data);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $this->assertDatabaseHas('pageable_site', [
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'site_id' => $new_site->id,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $new_site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $new_site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'site_id' => $old_site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'site_id' => $old_site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
        ]);
        $this->assertDatabaseMissing('pages', [
            'site_id' => $old_site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
        ]);
    }

    public function test_syncing_linked_manufacturers()
    {
        $manufacturerToLink = $this->createManufacturer();
        $manufacturer = $this->createManufacturer();

        $manufacturer->linkedToManufacturers()->sync([$manufacturerToLink->id]);
        $manufacturer->linkedByManufacturers()->sync([$manufacturer->id]);

        $manufacturerToLinkOnUpdate = factory(Manufacturer::class)->create();

        $data = $this->validData(['linked_manufacturer_ids' => [$manufacturerToLinkOnUpdate->id]]);

        $response = $this->submit($manufacturer, $data);

        $response->assertRedirect(route('admin.manufacturers.index'));

        $this->assertDatabaseHas('manufacturer_linked_manufacturers', [
            'manufacturer_id' => $manufacturer->id,
            'linked_manufacturer_id' => $manufacturerToLinkOnUpdate->id,
        ]);
        $this->assertDatabaseHas('manufacturer_linked_manufacturers', [
            'manufacturer_id' => $manufacturerToLinkOnUpdate->id,
            'linked_manufacturer_id' => $manufacturer->id,
        ]);

        $this->assertDatabaseMissing('manufacturer_linked_manufacturers', [
            'manufacturer_id' => $manufacturer->id,
            'linked_manufacturer_id' => $manufacturerToLink->id,
        ]);
        $this->assertDatabaseMissing('manufacturer_linked_manufacturers', [
            'manufacturer_id' => $manufacturerToLink->id,
            'linked_manufacturer_id' => $manufacturer->id,
        ]);
    }

    /**
     * @dataProvider imageValidationProvider
     */
    public function test_image_validation(string $inputName): void
    {
        $manufacturer = $this->createManufacturer();
        $data = $this->validData([
            $inputName => 'abc',
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function imageValidationProvider(): array
    {
        return [
            ['logo'],
            ['caravan_image'],
            ['motorhome_image'],
        ];
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $manufacturer = $this->createManufacturer();
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['exclusive'],
        ];
    }

    public function test_name_is_unique()
    {
        $manufacturer = $this->createManufacturer();
        $otherManufacturer = $this->createManufacturer();
        $data = $this->validData([
            'name' => $otherManufacturer->name,
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertSessionHasErrors('name');
    }

    private function submit(Manufacturer $manufacturer, array $data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($manufacturer);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validData($overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'motorhome_position' => 0,
            'caravan_position' => 0,
            'exclusive' => false,
            'caravan_image' => UploadedFile::fake()->image('avatar.jpg'),
            'motorhome_image' => UploadedFile::fake()->image('avatar.jpg'),
            'logo' => UploadedFile::fake()->image('avatar.jpg'),
            'link_directly_to_stock_search' => false,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(Manufacturer $manufacturer)
    {
        return route('admin.manufacturers.update', $manufacturer);
    }

    private function createManufacturer($attributes = [])
    {
        return factory(Manufacturer::class)->create($attributes);
    }
}

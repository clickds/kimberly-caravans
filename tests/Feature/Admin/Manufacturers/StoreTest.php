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

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->fakeStorage();
        $site = factory(Site::class)->create();
        $data = $this->validData([
            'site_ids' => [$site->id],
        ]);

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $manufacturerData = Arr::except($data, ['site_ids', 'logo', 'motorhome_image', 'caravan_image']);
        $this->assertDatabaseHas('manufacturers', $manufacturerData);
        $manufacturer = Manufacturer::orderBy('id', 'desc')->first();
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
        $site = $this->createSite();
        $data = $this->validData();
        $data['site_ids'] = [$site->id];

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $manufacturer = Manufacturer::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('pageable_site', [
            'site_id' => $site->id,
            'pageable_id' => $manufacturer->id,
            'pageable_type' => Manufacturer::class,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
        ]);
    }

    public function test_syncing_linked_manufacturers()
    {
        $manufacturerToLink = factory(Manufacturer::class)->create();

        $data = $this->validData(['linked_manufacturer_ids' => [$manufacturerToLink->id]]);

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.manufacturers.index'));

        $manufacturer = Manufacturer::orderBy('id', 'desc')->first();

        $this->assertDatabaseHas('manufacturer_linked_manufacturers', [
            'manufacturer_id' => $manufacturer->id,
            'linked_manufacturer_id' => $manufacturerToLink->id,
        ]);

        $this->assertDatabaseHas('manufacturer_linked_manufacturers', [
            'manufacturer_id' => $manufacturerToLink->id,
            'linked_manufacturer_id' => $manufacturer->id,
        ]);
    }

    /**
     * @dataProvider imageValidationProvider
     */
    public function test_image_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => 'abc',
        ]);

        $response = $this->submit($data);

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

    public function test_name_is_unique()
    {
        $manufacturer = $this->createManufacturer();
        $data = $this->validData([
            'name' => $manufacturer->name,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['exclusive'],
            ['logo'],
        ];
    }

    private function submit($data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
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

    private function url()
    {
        return route('admin.manufacturers.store');
    }

    private function createManufacturer($attributes = [])
    {
        return factory(Manufacturer::class)->create($attributes);
    }
}

<?php

namespace Tests\Feature\Admin\PopUps;

use App\Models\CaravanRange;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\PopUp;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider required_fields_provider
     */
    public function test_required_fields(string $requiredFieldName)
    {
        $data = $this->validFields([
            $requiredFieldName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function required_fields_provider(): array
    {
        return [
            ['name'],
            ['appears_on_all_pages'],
            ['appears_on_new_motorhome_pages'],
            ['appears_on_new_caravan_pages'],
            ['appears_on_used_motorhome_pages'],
            ['appears_on_used_caravan_pages'],
            ['live'],
            ['site_id'],
            ['mobile_image'],
            ['desktop_image'],
        ];
    }

    public function test_site_id_exists()
    {
        $data = $this->validFields([
            'site_id' => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('site_id');
    }

    public function test_either_page_id_or_external_url_must_be_present()
    {
        $data = $this->validFields([
            'page_id' => '',
            'external_url' => '',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('external_url');
        $response->assertSessionHasErrors('page_id');
    }

    public function test_page_id_must_exist()
    {
        $data = $this->validFields([
            'page_id' => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('page_id');
    }

    public function test_external_url_must_be_a_valid_url()
    {
        $data = $this->validFields([
            'external_url' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('external_url');
    }

    /**
     * @dataProvider habtmExistsProvider
     */
    public function test_has_and_belongs_to_many_exists_validation(string $inputName): void
    {
        $data = $this->validFields([
            $inputName => [0],
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName . '.0');
    }

    public function habtmExistsProvider(): array
    {
        return [
            ['caravan_range_ids'],
            ['motorhome_range_ids'],
            ['appears_on_page_ids'],
        ];
    }

    public function test_creating_when_page_id_set()
    {
        $this->fakeStorage();
        $page = factory(Page::class)->create();
        $data = $this->validFields([
            'page_id' => $page->id,
            'external_url' => null,
        ]);

        $response = $this->submit($data);

        $response->assertRedirect($this->redirectUrl());
        $popUpData = Arr::except($data, ['mobile_image', 'desktop_image']);
        $this->assertDatabaseHas('pop_ups', $popUpData);
        $popUp = PopUp::first();
        $this->assertFileExists($popUp->getFirstMedia('mobileImage')->getPath());
        $this->assertFileExists($popUp->getFirstMedia('desktopImage')->getPath());
    }

    public function test_creating_when_external_url_set()
    {
        $this->fakeStorage();
        $data = $this->validFields([
            'page_id' => null,
            'external_url' => 'https://www.google.co.uk',
        ]);

        $response = $this->submit($data);

        $response->assertRedirect($this->redirectUrl());
        $popUpData = Arr::except($data, ['mobile_image', 'desktop_image']);
        $this->assertDatabaseHas('pop_ups', $popUpData);
        $popUp = PopUp::first();
        $this->assertFileExists($popUp->getFirstMedia('mobileImage')->getPath());
        $this->assertFileExists($popUp->getFirstMedia('desktopImage')->getPath());
    }

    public function test_creating_with_associations()
    {
        $caravanRange = factory(CaravanRange::class)->create();
        $motorhomeRange = factory(MotorhomeRange::class)->create();
        $page = factory(Page::class)->create();
        $this->fakeStorage();
        $data = $this->validFields([
            'caravan_range_ids' => [$caravanRange->id],
            'motorhome_range_ids' => [$motorhomeRange->id],
            'appears_on_page_ids' => [$page->id],
        ]);

        $response = $this->submit($data);

        $response->assertRedirect($this->redirectUrl());
        $popUp = PopUp::first();
        $this->assertDatabaseHas('caravan_range_pop_up', [
            'caravan_range_id' => $caravanRange->id,
            'pop_up_id' => $popUp->id,
        ]);
        $this->assertDatabaseHas('motorhome_range_pop_up', [
            'motorhome_range_id' => $motorhomeRange->id,
            'pop_up_id' => $popUp->id,
        ]);
        $this->assertDatabaseHas('pop_up_appears_on_pages', [
            'page_id' => $page->id,
            'pop_up_id' => $popUp->id,
        ]);
    }


    private function redirectUrl(): string
    {
        return route('admin.pop-ups.index');
    }

    private function validFields(array $overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'external_url' => 'https://www.google.co.uk',
            'live' => true,
            'published_at' => null,
            'expired_at' => null,
            'mobile_image' => UploadedFile::fake()->image('avatar.jpg'),
            'desktop_image' => UploadedFile::fake()->image('avatar.jpg'),
            'appears_on_all_pages' => false,
            'appears_on_new_motorhome_pages' => false,
            'appears_on_new_caravan_pages' => false,
            'appears_on_used_motorhome_pages' => false,
            'appears_on_used_caravan_pages' => false,
        ];

        if (!array_key_exists('page_id', $overrides)) {
            $defaults['page_id'] = factory(Page::class)->create()->id;
        }

        if (!array_key_exists('site_id', $overrides)) {
            $defaults['site_id'] = factory(Site::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.pop-ups.store');

        return $this->actingAs($user)->post($url, $data);
    }
}

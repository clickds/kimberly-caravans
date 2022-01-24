<?php

namespace Tests\Feature\Admin\Pages;

use App\Models\ImageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Page;
use Illuminate\Testing\TestResponse;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validFields([$inputName => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['meta_title'],
            ['live'],
            ['template'],
            ['variety'],
            ['site_id'],
        ];
    }

    public function test_template_must_be_a_valid_template()
    {
        $data = $this->validFields(['template' => 'blah']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('template');
    }

    public function test_template_cannot_be_a_valid_non_standard_template()
    {
        $data = $this->validFields(['template' => 'blah']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('template');
    }

    public function test_variety_is_in_the_varieties_constant()
    {
        $data = $this->validFields(['variety' => 'abc']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('variety');
    }

    /**
     * @dataProvider existsProvider
     */
    public function test_exists_validation(string $inputName): void
    {
        $data = $this->validFields([$inputName => '0']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function existsProvider(): array
    {
        return [
            ['site_id'],
            ['parent_id'],
            ['video_banner_id'],
        ];
    }

    public function test_image_banner_exists_validation(): void
    {
        $data = $this->validFields([
            'image_banner_ids' => [0],
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('image_banner_ids.*');
    }

    public function test_successful()
    {
        $data = $this->validFields();

        $response = $this->submit($data);

        $redirectUrl = $this->redirectUrl();
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('pages', $data);
    }

    public function test_successful_with_image_banner()
    {
        $imageBanner = factory(ImageBanner::class)->create();
        $data = $this->validFields([
            'image_banner_ids' => [$imageBanner->id],
        ]);

        $response = $this->submit($data);

        $redirectUrl = $this->redirectUrl();
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('image_banner_page', [
            'image_banner_id' => $imageBanner->id,
        ]);
    }

    private function submit(array $data): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'meta_title' => 'some name',
            'template' => array_keys(Page::STANDARD_TEMPLATES)[0],
            'variety' => Page::VARIETIES[0],
            'live' => true,
        ];

        if (!array_key_exists('site_id', $overrides)) {
            $defaults['site_id'] = $this->createSite()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function url(): string
    {
        return route('admin.pages.store');
    }

    private function redirectUrl(): string
    {
        return route('admin.pages.index');
    }
}

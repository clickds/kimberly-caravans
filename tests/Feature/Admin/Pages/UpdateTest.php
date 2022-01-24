<?php

namespace Tests\Feature\Admin\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Page;
use App\Models\ImageBanner;
use Illuminate\Testing\TestResponse;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $page = $this->createPage();
        $data = $this->validFields([$inputName => '']);

        $response = $this->submit($page, $data);

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

    public function test_template_must_be_in_page_templates_constant()
    {
        $page = $this->createPage();
        $data = $this->validFields(['template' => 'blah']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('template');
    }

    public function test_template_must_be_in_page_varieties_constant()
    {
        $page = $this->createPage();
        $data = $this->validFields(['variety' => 'blah']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('variety');
    }

    /**
     * @dataProvider existsProvider
     */
    public function test_exists_validation(string $inputName): void
    {
        $page = $this->createPage();
        $data = $this->validFields([$inputName => '0']);

        $response = $this->submit($page, $data);

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
        $page = $this->createPage();
        $data = $this->validFields([
            'image_banner_ids' => [0],
        ]);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('image_banner_ids.0');
    }

    public function test_successful()
    {
        $page = $this->createPage();
        $data = $this->validFields();

        $response = $this->submit($page, $data);

        $response->assertRedirect($this->redirectUrl());
        $this->assertDatabaseHas('pages', array_merge($data, ['id' => $page->id]));
    }

    public function test_successful_with_image_banner()
    {
        $imageBanner = factory(ImageBanner::class)->create();
        $page = $this->createPage();
        $data = $this->validFields([
            'image_banner_ids' => [$imageBanner->id],
        ]);

        $response = $this->submit($page, $data);

        $response->assertRedirect($this->redirectUrl());
        $this->assertDatabaseHas('image_banner_page', [
            'image_banner_id' => $imageBanner->id,
        ]);
    }

    public function test_slug_is_regenerated_if_page_name_is_updated(): void
    {
        $originalPage = $this->createPage();
        $originalSlug = $originalPage->slug;

        $data = $this->validFields(['name' => 'An updated name']);
        $this->submit($originalPage, $data);
        $updatedPage = Page::where('id', $originalPage->id)->first();

        $this->assertNotEquals($originalSlug, $updatedPage->slug);
        $this->assertEquals('an-updated-name', $updatedPage->slug);
    }

    private function submit(Page $page, array $data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url($page);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'meta_title' => 'some name',
            'template' => array_keys(Page::STANDARD_TEMPLATES)[0],
            'variety' => Page::VARIETIES[0],
            'live' => false,
        ];

        if (!array_key_exists('site_id', $overrides)) {
            $defaults['site_id'] = $this->createSite()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function url($page): string
    {
        return route('admin.pages.update', $page);
    }

    private function redirectUrl(): string
    {
        return route('admin.pages.index');
    }

    private function createPage($attributes = []): Page
    {
        return factory(Page::class)->create($attributes);
    }
}

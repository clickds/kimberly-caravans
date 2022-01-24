<?php

namespace Tests\Feature\Admin\Buttonable\ImageBannerButtons;

use App\Models\Button;
use App\Models\ImageBanner;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $imageBanner = $this->createImageBanner();
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($imageBanner, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['colour'],
        ];
    }

    /**
     * @dataProvider requiredIfOtherBlankProvider
     */
    public function test_required_if_other_blank(string $inputName, string $otherInputName): void
    {
        $imageBanner = $this->createImageBanner();
        $data = $this->validData([
            $inputName => null,
            $otherInputName => null,
        ]);

        $response = $this->submit($imageBanner, $data);

        $response->assertSessionHasErrors($inputName);
        $response->assertSessionHasErrors($otherInputName);
    }

    public function requiredIfOtherBlankProvider(): array
    {
        return [
            ['external_url', 'link_page_id'],
        ];
    }

    public function test_external_url_is_a_url(): void
    {
        $imageBanner = $this->createImageBanner();
        $data = $this->validData([
            'external_url' => 'abc',
        ]);

        $response = $this->submit($imageBanner, $data);

        $response->assertSessionHasErrors('external_url');
    }

    public function test_linked_page_must_exist(): void
    {
        $imageBanner = $this->createImageBanner();
        $data = $this->validData([
            'link_page_id' => 0,
        ]);

        $response = $this->submit($imageBanner, $data);

        $response->assertSessionHasErrors('link_page_id');
    }

    public function test_successful(): void
    {
        $imageBanner = $this->createImageBanner();
        $data = $this->validData();

        $response = $this->submit($imageBanner, $data);

        $response->assertRedirect(route('admin.image-banners.buttons.index', $imageBanner));
        $this->assertDatabaseHas('buttons', $data);
    }

    private function submit(ImageBanner $imageBanner, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.image-banners.buttons.store', $imageBanner);

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'external_url' => 'https://www.google.co.uk',
            'colour' => $this->faker->randomElement(array_keys(Button::COLOURS)),
            'position' => 1,
        ];

        if (!array_key_exists('link_page_id', $overrides)) {
            $page = factory(Page::class)->create();
            $defaults['link_page_id'] = $page->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function createImageBanner(array $attributes = [])
    {
        return factory(ImageBanner::class)->create($attributes);
    }
}

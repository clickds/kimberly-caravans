<?php

namespace Tests\Feature\Admin\ImageBanners;

use App\Models\ImageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_fields(string $inputName): void
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
            ['title'],
            ['title_text_colour'],
            ['image'],
            ['icon'],
            ['text_alignment'],
            ['live'],
        ];
    }

    /**
     * @dataProvider textColourInputs
     */
    public function test_valid_text_colours(string $inputName): void
    {
        $data = $this->validData([
            $inputName => 'blah',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function textColourInputs(): array
    {
        return [
            ['title_text_colour'],
            ['content_text_colour'],
        ];
    }

    /**
     * @dataProvider backgroundColourInputs
     */
    public function test_valid_background_colours(string $inputName): void
    {
        $data = $this->validData([
            $inputName => 'blah',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function backgroundColourInputs(): array
    {
        return [
            ['title_background_colour'],
            ['content_background_colour'],
        ];
    }

    public function test_successfully_updates_image_banner(): void
    {
        $this->fakeStorage();
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.image-banners.index'));
        $bannerData = Arr::except($data, ['image', 'image_alt']);
        $this->assertDatabaseHas('image_banners', $bannerData);
        $this->assertDatabaseHas('media', [
            'name' => $data['image_alt'],
            'file_name' => $data['image']->getClientOriginalName(),
        ]);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.image-banners.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'title' => $this->faker->name,
            'title_background_colour' => $this->faker->randomElement(array_keys(ImageBanner::BACKGROUND_COLOURS)),
            'title_text_colour' => $this->faker->randomElement(array_keys(ImageBanner::TEXT_COLOURS)),
            'content' => $this->faker->paragraph,
            'content_background_colour' => $this->faker->randomElement(array_keys(ImageBanner::BACKGROUND_COLOURS)),
            'content_text_colour' => $this->faker->randomElement(array_keys(ImageBanner::TEXT_COLOURS)),
            'position' => 0,
            'image' => UploadedFile::fake()->image('image.jpg', 1980, 400),
            'image_alt' => 'something',
            'icon' => $this->faker->randomElement(ImageBanner::ICONS),
            'text_alignment' => $this->faker->randomElement(array_keys(ImageBanner::TEXT_ALIGNMENTS)),
            'published_at' => null,
            'expired_at' => null,
            'live' => true,
        ];

        return array_merge($defaults, $overrides);
    }
}

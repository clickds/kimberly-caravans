<?php

namespace Tests\Feature\Admin\StockSearchLinks;

use App\Models\Page;
use App\Models\Site;
use App\Models\StockSearchLink;
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
            ['type'],
            ['image'],
            ['mobile_image'],
            ['site_id'],
            ['page_id'],
        ];
    }

    /**
     * @dataProvider existsProvider
     */
    public function test_exists_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function existsProvider(): array
    {
        return [
            ['site_id'],
            ['page_id'],
        ];
    }

    public function test_type_must_be_in_types_constant(): void
    {
        $data = $this->validData([
            'type' => 'wrong',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('type');
    }

    public function test_image_must_be_an_image(): void
    {
        $data = $this->validData([
            'image' => 'wrong',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('image');
    }
    public function test_mobile_image_must_be_an_image(): void
    {
        $data = $this->validData([
            'mobile_image' => 'wrong',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('mobile_image');
    }

    public function tests_successful(): void
    {
        $this->fakeStorage();
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect();
        $coreData = Arr::except($data, ['image', 'mobile_image']);
        $this->assertDatabaseHas('stock_search_links', $coreData);
        $this->assertDatabaseHas('media', [
            'file_name' => 'avatar.jpg',
        ]);
        $this->assertDatabaseHas('media', [
            'file_name' => 'mobile-avatar.jpg',
        ]);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(StockSearchLink::TYPES),
            'image' => UploadedFile::fake()->image('avatar.jpg', 640, 200),
            'mobile_image' => UploadedFile::fake()->image('mobile-avatar.jpg', 400, 400),
        ];

        if (!array_key_exists('site_id', $overrides)) {
            $defaults['site_id'] = factory(Site::class)->create()->id;
        }

        if (!array_key_exists('page_id', $overrides)) {
            $defaults['page_id'] = factory(Page::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.stock-search-links.store');

        return $this->actingAs($user)->post($url, $data);
    }
}

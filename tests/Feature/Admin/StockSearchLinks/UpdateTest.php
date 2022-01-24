<?php

namespace Tests\Feature\Admin\StockSearchLinks;

use App\Models\Page;
use App\Models\Site;
use App\Models\StockSearchLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
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
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect();
        $this->assertDatabaseHas('stock_search_links', $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(StockSearchLink::TYPES),
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
        $stockSearchLink = factory(StockSearchLink::class)->create();
        $url = route('admin.stock-search-links.update', $stockSearchLink);

        return $this->actingAs($user)->put($url, $data);
    }
}

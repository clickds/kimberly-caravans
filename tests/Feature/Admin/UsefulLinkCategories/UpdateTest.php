<?php

namespace Tests\Feature\Admin\UsefulLinkCategories;

use App\Models\UsefulLinkCategory;
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
        $usefulLinkCategory = factory(UsefulLinkCategory::class)->create();
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($usefulLinkCategory, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
        ];
    }

    public function test_name_is_unique(): void
    {
        $usefulLinkCategory = factory(UsefulLinkCategory::class)->create();
        $category = factory(UsefulLinkCategory::class)->create();
        $data = $this->validData([
            'name' => $category->name,
        ]);

        $response = $this->submit($usefulLinkCategory, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successfully_creates_category(): void
    {
        $usefulLinkCategory = factory(UsefulLinkCategory::class)->create();
        $data = $this->validData();

        $response = $this->submit($usefulLinkCategory, $data);

        $response->assertRedirect(route('admin.useful-link-categories.index'));
        $this->assertDatabaseHas('useful_link_categories', $data);
    }

    private function submit(UsefulLinkCategory $usefulLinkCategory, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.useful-link-categories.update', $usefulLinkCategory);

        return $this->actingAs($user)->put($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->unique()->name,
            'position' => 0,
        ];

        return array_merge($defaults, $overrides);
    }
}

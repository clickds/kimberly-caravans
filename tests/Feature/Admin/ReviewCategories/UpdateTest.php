<?php

namespace Tests\Feature\Admin\ReviewCategories;

use App\Models\ReviewCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $reviewCategory = factory(ReviewCategory::class)->create();

        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($reviewCategory, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider(): array
    {
        return [
            ['name'],
        ];
    }

    public function test_name_must_be_unique()
    {
        $existingReviewCategory = factory(ReviewCategory::class)->create();

        $reviewCategory = factory(ReviewCategory::class)->create();

        $data = $this->validData(['name' => $existingReviewCategory->name]);

        $response = $this->submit($reviewCategory, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successfully_updates_review_category()
    {
        $reviewCategory = factory(ReviewCategory::class)->create();

        $data = $this->validData();

        $response = $this->submit($reviewCategory, $data);

        $response->assertRedirect($this->redirectUrl());

        $this->assertDatabaseHas('review_categories', $data);
    }

    private function redirectUrl(): string
    {
        return route('admin.review-categories.index');
    }

    private function submit(ReviewCategory $reviewCategory, array $data): TestResponse
    {
        $user = $this->createSuperUser();

        $url = route('admin.review-categories.update', $reviewCategory);

        return $this->actingAs($user)->put($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
        ];

        return array_merge($defaults, $overrides);
    }
}

<?php

namespace Tests\Feature\Admin\ReviewCategories;

use App\Models\ReviewCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_deletes_review_category(): void
    {
        $reviewCategory = factory(ReviewCategory::class)->create();

        $response = $this->submit($reviewCategory);

        $response->assertRedirect(route('admin.review-categories.index'));

        $this->assertDatabaseMissing('review_categories', $reviewCategory->getAttributes());
    }

    private function submit(ReviewCategory $reviewCategory): TestResponse
    {
        $user = $this->createSuperUser();

        $url = route('admin.review-categories.destroy', $reviewCategory);

        return $this->actingAs($user)->delete($url);
    }
}

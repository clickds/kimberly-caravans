<?php

namespace Tests\Feature\Admin\Reviews;

use App\Models\Review;
use App\Models\ReviewCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_deletes_review(): void
    {
        $reviewCategory = factory(ReviewCategory::class)->create();
        $review = factory(Review::class)->create(['review_category_id' => $reviewCategory->id]);

        $response = $this->submit($review);

        $response->assertRedirect(route('admin.reviews.index'));
        $this->assertDatabaseMissing('reviews', $review->getAttributes());
    }

    private function submit(Review $review): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.reviews.destroy', $review);

        return $this->actingAs($user)->delete($url);
    }
}

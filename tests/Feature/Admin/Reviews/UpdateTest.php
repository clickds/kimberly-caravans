<?php

namespace Tests\Feature\Admin\Reviews;

use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\MotorhomeRange;
use App\Models\Review;
use App\Models\ReviewCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $review = $this->createReview();
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($review, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider(): array
    {
        return [
            ['review_category_id'],
            ['title'],
            ['text'],
            ['magazine'],
            ['date'],
        ];
    }

    public function test_link_is_required_if_review_has_no_file_and_input_empty()
    {
        $review = $this->createReview();
        $data = $this->validData([
            'review_file' => null,
            'link' => null,
        ]);

        $response = $this->submit($review, $data);

        $response->assertSessionHasErrors('link');
    }

    public function test_link_is_not_required_if_review_has_file_and_input_empty()
    {
        $review = $this->createReview();
        factory(Media::class)->create([
            'model_id' => $review->id,
            'model_type' => Review::class,
            'collection_name' => 'review_file',
        ]);
        $data = $this->validData([
            'review_file' => null,
            'link' => null,
        ]);

        $response = $this->submit($review, $data);

        $response->assertRedirect(route('admin.reviews.index'));
        $reviewData = Arr::except($data, ['image', 'review_file']);
        $this->assertDatabaseHas('reviews', $reviewData);
    }

    /**
     * @dataProvider existsProvider
     */
    public function test_exists_validation(string $inputName): void
    {
        $review = $this->createReview();
        $data = $this->validData([
            $inputName => 0,
        ]);

        $response = $this->submit($review, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function existsProvider(): array
    {
        return [
            ['dealer_id'],
        ];
    }

    /**
     * @dataProvider habtmExistsProvider
     */
    public function test_has_and_belongs_to_many_exists_validation(string $inputName): void
    {
        $review = $this->createReview();
        $data = $this->validData([
            $inputName => [0],
        ]);

        $response = $this->submit($review, $data);

        $response->assertSessionHasErrors($inputName . '.0');
    }

    public function habtmExistsProvider(): array
    {
        return [
            ['caravan_range_ids'],
            ['motorhome_range_ids'],
        ];
    }

    public function test_successfully_creates_review()
    {
        $this->fakeStorage();
        $review = $this->createReview();
        $motorhomeRange = factory(MotorhomeRange::class)->create();
        $caravanRange = factory(CaravanRange::class)->create();
        $dealer = factory(Dealer::class)->create();
        $data = $this->validData([
            'dealer_id' => $dealer->id,
            'caravan_range_ids' => [$caravanRange->id],
            'motorhome_range_ids' => [$motorhomeRange->id],
        ]);

        $response = $this->submit($review, $data);

        $response->assertRedirect($this->redirectUrl());

        $reviewData = Arr::except($data, ['image', 'review_file', 'motorhome_range_ids', 'caravan_range_ids']);
        $this->assertDatabaseHas('reviews', $reviewData);
        $this->assertDatabaseHas('caravan_range_review', [
            'review_id' => $review->id,
            'caravan_range_id' => $caravanRange->id,
        ]);
        $this->assertDatabaseHas('motorhome_range_review', [
            'review_id' => $review->id,
            'motorhome_range_id' => $motorhomeRange->id,
        ]);
        $this->assertFileExists($review->getFirstMedia('image')->getPath());
        $this->assertFileExists($review->getFirstMedia('review_file')->getPath());
    }

    private function createReview(): Review
    {
        $reviewCategory = factory(ReviewCategory::class)->create();

        return factory(Review::class)->create(['review_category_id' => $reviewCategory->id]);
    }

    private function redirectUrl(): string
    {
        return route('admin.reviews.index');
    }

    private function submit(Review $review, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.reviews.update', $review);

        return $this->actingAs($user)->put($url, $data);
    }

    private function validData(array $overrides = [])
    {
        $defaults = [
            'date' => $this->faker->date(),
            'title' => 'some title',
            'magazine' => 'caravan monthly',
            'text' => 'some content',
            'image' => UploadedFile::fake()->image('something.jpg'),
            'review_file' => UploadedFile::fake()->create('something.pdf'),
            'link' => 'https://www.google.co.uk',
        ];

        if (!array_key_exists('review_category_id', $overrides)) {
            $defaults['review_category_id'] = factory(ReviewCategory::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }
}

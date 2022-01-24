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
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider(): array
    {
        return [
            ['title'],
            ['text'],
            ['magazine'],
            ['date'],
            ['image'],
        ];
    }

    /**
     * @dataProvider requiredWithoutProvider
     */
    public function test_required_without_validation(string $inputName, string $otherInputName): void
    {
        $data = $this->validData([
            $inputName => null,
            $otherInputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
        $response->assertSessionHasErrors($otherInputName);
    }

    public function requiredWithoutProvider(): array
    {
        return [
            ['link', 'review_file'],
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
            ['dealer_id'],
        ];
    }

    /**
     * @dataProvider habtmExistsProvider
     */
    public function test_has_and_belongs_to_many_exists_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => [0],
        ]);

        $response = $this->submit($data);

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
        $dealer = factory(Dealer::class)->create();
        $motorhomeRange = factory(MotorhomeRange::class)->create();
        $caravanRange = factory(CaravanRange::class)->create();
        $data = $this->validData([
            'dealer_id' => $dealer->id,
            'caravan_range_ids' => [$caravanRange->id],
            'motorhome_range_ids' => [$motorhomeRange->id],
        ]);

        $response = $this->submit($data);

        $response->assertRedirect($this->redirectUrl());

        $reviewData = Arr::except($data, ['image', 'review_file', 'caravan_range_ids', 'motorhome_range_ids']);
        $this->assertDatabaseHas('reviews', $reviewData);

        $review = Review::first();
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

    private function redirectUrl(): string
    {
        return route('admin.reviews.index');
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.reviews.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = [])
    {
        $reviewCategory = factory(ReviewCategory::class)->create();

        $defaults = [
            'review_category_id' => $reviewCategory->id,
            'date' => $this->faker->date(),
            'title' => 'some title',
            'magazine' => 'caravan monthly',
            'text' => 'some content',
            'image' => UploadedFile::fake()->image('something.jpg'),
            'review_file' => UploadedFile::fake()->create('something.pdf'),
            'link' => 'https://www.google.co.uk',
        ];

        return array_merge($defaults, $overrides);
    }
}

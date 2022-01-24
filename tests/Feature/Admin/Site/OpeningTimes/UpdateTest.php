<?php

namespace Tests\Feature\Admin\Site\OpeningTimes;

use App\Models\OpeningTime;
use Carbon\Carbon;
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
    public function test_required_validation(string $inputKey): void
    {
        $data = $this->validData([
            $inputKey => null,
        ]);
        $openingTime = factory(OpeningTime::class)->create();

        $response = $this->submit($openingTime, $data);

        $response->assertSessionHasErrors($inputKey);
    }

    public function requiredProvider(): array
    {
        return [
            ['day'],
            ['opens_at'],
            ['closes_at'],
            ['closed'],
        ];
    }

    public function test_day_must_be_unique_per_site(): void
    {
        $otherOpeningTime = factory(OpeningTime::class)->create();
        $openingTime = factory(OpeningTime::class)->create([
            'site_id' => $otherOpeningTime->site_id,
        ]);
        $data = $this->validData([
            'day' => $otherOpeningTime->day,
        ]);

        $response = $this->submit($openingTime, $data);

        $response->assertSessionHasErrors('day');
    }

    public function test_closing_time_must_be_after_opening_time(): void
    {
        $openingTime = factory(OpeningTime::class)->create();
        $data = $this->validData([
            'opens_at' => '17:30:00',
            'closes_at' => '09:00:00',
        ]);

        $response = $this->submit($openingTime, $data);

        $response->assertSessionHasErrors('opens_at');
        $response->assertSessionHasErrors('closes_at');
    }

    public function test_times_are_times(): void
    {
        $openingTime = factory(OpeningTime::class)->create();
        $data = $this->validData([
            'opens_at' => 'abc',
            'closes_at' => 'abc',
        ]);

        $response = $this->submit($openingTime, $data);

        $response->assertSessionHasErrors('opens_at');
        $response->assertSessionHasErrors('closes_at');
    }

    public function test_successful(): void
    {
        $openingTime = factory(OpeningTime::class)->create();
        $data = $this->validData();

        $response = $this->submit($openingTime, $data);

        $response->assertRedirect(route('admin.sites.opening-times.index', $openingTime->site));
        $this->assertDatabaseHas('opening_times', $data);
    }

    private function submit(OpeningTime $openingTime, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.sites.opening-times.update', [
            'site' => $openingTime->site,
            'opening_time' => $openingTime,
        ]);

        return $this->actingAs($user)->put($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'day' => $this->faker->randomElement(array_keys(Carbon::getDays())),
            'opens_at' => '09:00:00',
            'closes_at' => '17:30:00',
            'closed' => false,
        ];

        return array_merge($defaults, $overrides);
    }
}

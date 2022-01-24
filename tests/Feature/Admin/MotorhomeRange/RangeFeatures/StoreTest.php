<?php

namespace Tests\Feature\Admin\MotorhomeRange\RangeFeatures;

use App\Models\MotorhomeRange;
use App\Models\RangeFeature;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_feature_successfully(): void
    {
        $range = $this->createRange();
        $data = $this->validData();

        $response = $this->submit($range, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.range-features.index', $range));
        $this->assertDatabaseHas('range_features', $data);
    }

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $range = $this->createRange();
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($range, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider(): array
    {
        return [
            ['name'],
            ['content'],
            ['key'],
            ['warranty'],
        ];
    }

    /**
     * @dataProvider booleanValidationProvider
     */
    public function test_boolean_validation(string $inputName): void
    {
        $range = $this->createRange();
        $data = $this->validData([
            $inputName => 'abc',
        ]);

        $response = $this->submit($range, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function booleanValidationProvider(): array
    {
        return [
            ['key'],
            ['warranty'],
        ];
    }

    public function test_site_exists_validation(): void
    {
        $range = $this->createRange();
        $data = $this->validData([
            'site_ids' => [0],
        ]);

        $response = $this->submit($range, $data);

        $response->assertSessionHasErrors('site_ids.0');
    }

    private function submit(MotorhomeRange $range, array $data = []): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($range);

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'content' => 'some content',
            'key' => true,
            'name' => 'some name',
            'position' => 1,
            'warranty' => true,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(MotorhomeRange $range): string
    {
        return route('admin.motorhome-ranges.range-features.store', $range);
    }

    private function createRangeFeature(MotorhomeRange $motorhomeRange, $attributes = [])
    {
        $attributes = array_merge($attributes, [
            'vehicle_range_id' => $motorhomeRange->id,
            'vehicle_range_type' => MotorhomeRange::class,
        ]);
        return factory(RangeFeature::class)->create($attributes);
    }

    private function createRange($attributes = []): MotorhomeRange
    {
        return factory(MotorhomeRange::class)->create($attributes);
    }
}

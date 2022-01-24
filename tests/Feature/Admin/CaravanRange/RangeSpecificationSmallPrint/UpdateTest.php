<?php

namespace Tests\Feature\Admin\CaravanRange\RangeSpecificationSmallPrint;

use App\Models\CaravanRange;
use App\Models\RangeSpecificationSmallPrint;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

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
            ['name'],
            ['content'],
        ];
    }

    /**
     * @dataProvider existsValidationProvider
     */
    public function test_exists_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function existsValidationProvider(): array
    {
        return [
            ['site_id'],
        ];
    }

    public function test_name_is_unique_validation(): void
    {
        $smallPrint = $this->createSmallPrint();
        $otherSmallPrint = $this->createSmallPrint([
            'vehicle_range_id' => $smallPrint->vehicle_range_id,
            'vehicle_range_type' => $smallPrint->vehicle_range_type,
        ]);
        $data = $this->validData([
            'site_id' => $otherSmallPrint->site_id,
            'name' => $otherSmallPrint->name,
        ]);

        $response = $this->submit($data, $smallPrint);

        $response->assertSessionHasErrors('name');
    }

    public function test_successful()
    {
        $smallPrint = $this->createSmallPrint();
        $data = $this->validData();

        $response = $this->submit($data, $smallPrint);

        $response->assertRedirect($this->redirectUrl($smallPrint->vehicleRange));
        $this->assertDatabaseHas('range_specification_small_prints', $data);
    }

    private function submit(array $data, RangeSpecificationSmallPrint $smallPrint = null): TestResponse
    {
        $user = $this->createSuperUser();
        if (is_null($smallPrint)) {
            $smallPrint = $this->createSmallPrint();
        }
        $url = $this->url($smallPrint);

        return $this->actingAs($user)->put($url, $data);
    }

    private function createSmallPrint(array $attributes = []): RangeSpecificationSmallPrint
    {
        return factory(RangeSpecificationSmallPrint::class)
            ->state('caravan-range')->create($attributes);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'content' => 'some content',
        ];

        if (!array_key_exists('site_id', $overrides)) {
            $defaults['site_id'] = factory(Site::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function url(RangeSpecificationSmallPrint $smallPrint): string
    {
        return route('admin.caravan-ranges.range-specification-small-prints.update', [
            'caravanRange' => $smallPrint->vehicleRange,
            'range_specification_small_print' => $smallPrint,
        ]);
    }

    private function redirectUrl(CaravanRange $range): string
    {
        return route('admin.caravan-ranges.range-specification-small-prints.index', $range);
    }
}

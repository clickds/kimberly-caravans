<?php

namespace Tests\Feature\Admin\RangeSpecificationSmallPrint\Clones;

use App\Models\CaravanRange;
use App\Models\RangeSpecificationSmallPrint;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation_for_caravan_range(string $vehicleRangeType, string $inputName)
    {
        $smallPrint = $this->createSmallPrint($vehicleRangeType);
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($smallPrint, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider()
    {
        return [
            ['caravan-range', 'site_id'],
            ['motorhome-range', 'site_id'],
        ];
    }

    /**
     * @dataProvider existsValidationProvider
     */
    public function test_exists_validation(string $vehicleRangeType, string $inputName)
    {
        $smallPrint = $this->createSmallPrint($vehicleRangeType);
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($smallPrint, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function existsValidationProvider()
    {
        return [
            ['caravan-range', 'site_id'],
            ['motorhome-range', 'site_id'],
        ];
    }

    /**
     * @dataProvider uniqueValidationProvider
     */
    public function test_unique_validation(string $vehicleRangeType, string $inputName)
    {
        $smallPrint = $this->createSmallPrint($vehicleRangeType);
        $data = $this->validData([
            $inputName => $smallPrint->$inputName,
        ]);

        $response = $this->submit($smallPrint, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function uniqueValidationProvider()
    {
        return [
            ['caravan-range', 'site_id'],
            ['motorhome-range', 'site_id'],
        ];
    }

    /**
     * @dataProvider cloneProvider
     */
    public function test_successfully_clones(string $vehicleRangeType)
    {
        $smallPrint = $this->createSmallPrint($vehicleRangeType);
        $site = factory(Site::class)->create();
        $data = $this->validData([
            'site_id' => $site->id,
        ]);

        $this->withoutExceptionHandling();
        $response = $this->submit($smallPrint, $data);

        $response->assertRedirect($this->redirectUrl($smallPrint));
        $this->assertDatabaseHas('range_specification_small_prints', [
            'site_id' => $site->id,
            'vehicle_range_type' => $smallPrint->vehicle_range_type,
            'vehicle_range_id' => $smallPrint->vehicle_range_id,
            'name' => $smallPrint->name . ' Clone',
            'content' => $smallPrint->content,
        ]);
    }

    public function cloneProvider()
    {
        return [
            ['caravan-range'],
            ['motorhome-range'],
        ];
    }

    private function submit(RangeSpecificationSmallPrint $smallPrint, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($smallPrint);

        return $this->actingAs($user)->post($url, $data);
    }

    private function redirectUrl(RangeSpecificationSmallPrint $smallPrint): string
    {
        switch ($smallPrint->vehicle_range_type) {
            case CaravanRange::class:
                return route('admin.caravan-ranges.range-specification-small-prints.index', $smallPrint->vehicleRange);
            default:
                return route('admin.motorhome-ranges.range-specification-small-prints.index', $smallPrint->vehicleRange);
        }
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [];

        if (!array_key_exists('site_id', $overrides)) {
            $defaults['site_id'] = factory(Site::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function url(RangeSpecificationSmallPrint $smallPrint): string
    {
        return route('admin.range-specification-small-prints.clones.store', $smallPrint);
    }

    private function createSmallPrint(string $type)
    {
        return factory(RangeSpecificationSmallPrint::class)->states($type)->create();
    }
}

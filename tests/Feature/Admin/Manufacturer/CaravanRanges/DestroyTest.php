<?php

namespace Tests\Feature\Admin\Manufacturer\CaravanRanges;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\CaravanRange;
use App\Models\Manufacturer;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $caravanRange = $this->createCaravanRange();

        $response = $this->submit($caravanRange);

        $response->assertRedirect(route('admin.manufacturers.caravan-ranges.index', $caravanRange->manufacturer));
        $this->assertDatabaseMissing('caravan_ranges', [
            'id' => $caravanRange->id,
        ]);
    }

    public function test_removes_site_pages()
    {
        $site = $this->createSite();
        $caravanRange = $this->createCaravanRange();
        $caravanRange->sites()->sync($site);
        $page = $this->createPageForPageable($caravanRange, $site);

        $response = $this->submit($caravanRange);

        $response->assertRedirect(route('admin.manufacturers.caravan-ranges.index', $caravanRange->manufacturer));
        $this->assertDatabaseMissing('caravan_ranges', [
            'id' => $caravanRange->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => CaravanRange::class,
            'pageable_id' => $caravanRange->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function submit(CaravanRange $caravanRange)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravanRange->manufacturer, $caravanRange);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Manufacturer $manufacturer, CaravanRange $caravanRange)
    {
        return route('admin.manufacturers.caravan-ranges.destroy', [
            'caravan_range' => $caravanRange,
            'manufacturer' => $manufacturer,
        ]);
    }

    private function createCaravanRange($attributes = [])
    {
        return factory(CaravanRange::class)->create($attributes);
    }
}

<?php

namespace Tests\Feature\Admin\Manufacturer\MotorhomeRanges;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MotorhomeRange;
use App\Models\Manufacturer;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $motorhomeRange = $this->createMotorhomeRange();

        $response = $this->submit($motorhomeRange);

        $response->assertRedirect(route('admin.manufacturers.motorhome-ranges.index', $motorhomeRange->manufacturer));
        $this->assertDatabaseMissing('motorhome_ranges', [
            'id' => $motorhomeRange->id,
        ]);
    }

    public function test_removes_site_pages()
    {
        $site = $this->createSite();
        $motorhomeRange = $this->createMotorhomeRange();
        $motorhomeRange->sites()->sync($site);
        $page = $this->createPageForPageable($motorhomeRange, $site);

        $response = $this->submit($motorhomeRange);

        $response->assertRedirect(route('admin.manufacturers.motorhome-ranges.index', $motorhomeRange->manufacturer));
        $this->assertDatabaseMissing('motorhome_ranges', [
            'id' => $motorhomeRange->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => MotorhomeRange::class,
            'pageable_id' => $motorhomeRange->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function submit(MotorhomeRange $motorhomeRange)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($motorhomeRange->manufacturer, $motorhomeRange);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Manufacturer $manufacturer, MotorhomeRange $motorhomeRange)
    {
        return route('admin.manufacturers.motorhome-ranges.destroy', [
            'motorhome_range' => $motorhomeRange,
            'manufacturer' => $manufacturer,
        ]);
    }

    private function createMotorhomeRange($attributes = [])
    {
        return factory(MotorhomeRange::class)->create($attributes);
    }
}

<?php

namespace Tests\Unit\Services\Pageable;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CaravanRange;
use App\Models\Page;
use App\Models\Site;
use App\Services\Pageable\CaravanRangePageSaver;

class CaravanRangePageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_caravan_range_page(): void
    {
        $site = $this->createSite();
        $range = factory(CaravanRange::class)->create();
        $saver = new CaravanRangePageSaver($range, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $range->name,
            "live" => true,
            "pageable_type" => CaravanRange::class,
            "pageable_id" => $range->id,
            "template" => Page::TEMPLATE_CARAVAN_RANGE,
        ]);
    }

    public function test_when_range_name_changed_page_slug_changes(): void
    {
        $site = $this->createSite();
        $range = factory(CaravanRange::class)->create([
            'name' => 'old',
        ]);
        $page = $this->createPage($range, $site);
        $range->name = 'new';
        $range->save();
        $saver = new CaravanRangePageSaver($range, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "id" => $page->id,
            "site_id" => $site->id,
            "name" => $range->name,
            "pageable_type" => CaravanRange::class,
            "pageable_id" => $range->id,
            "template" => Page::TEMPLATE_CARAVAN_RANGE,
        ]);
        $this->assertDatabaseMissing('pages', [
            "id" => $page->id,
            "slug" => $page->slug,
        ]);
    }

    private function createPage(CaravanRange $range, Site $site): Page
    {
        return factory(Page::class)->create([
            "site_id" => $site->id,
            "name" => $range->name,
            "pageable_type" => CaravanRange::class,
            "pageable_id" => $range->id,
            "template" => Page::TEMPLATE_CARAVAN_RANGE,
        ]);
    }
}

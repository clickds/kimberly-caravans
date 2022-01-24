<?php

namespace Tests\Unit\Services\Pageable;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Motorhome;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Site;
use App\Services\Pageable\MotorhomePageSaver;
use App\Services\Pageable\MotorhomeRangePageSaver;

class MotorhomeRangePageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_motorhome_range_page(): void
    {
        $site = $this->createSite();
        $range = factory(MotorhomeRange::class)->create();
        $saver = new MotorhomeRangePageSaver($range, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "live" => true,
            "name" => $range->name,
            "pageable_type" => MotorhomeRange::class,
            "pageable_id" => $range->id,
            "template" => Page::TEMPLATE_MOTORHOME_RANGE,
        ]);
    }

    public function test_when_range_name_changed_page_slug_changes(): void
    {
        $site = $this->createSite();
        $range = factory(MotorhomeRange::class)->create([
            'name' => 'old',
        ]);
        $page = $this->createPage($range, $site);
        $range->name = 'new';
        $range->save();
        $saver = new MotorhomeRangePageSaver($range, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "id" => $page->id,
            "site_id" => $site->id,
            "name" => $range->name,
            "pageable_type" => MotorhomeRange::class,
            "pageable_id" => $range->id,
            "template" => Page::TEMPLATE_MOTORHOME_RANGE,
        ]);
        $this->assertDatabaseMissing('pages', [
            "id" => $page->id,
            "slug" => $page->slug,
        ]);
    }

    private function createPage(MotorhomeRange $range, Site $site): Page
    {
        return factory(Page::class)->create([
            "site_id" => $site->id,
            "name" => $range->name,
            "pageable_type" => MotorhomeRange::class,
            "pageable_id" => $range->id,
            "template" => Page::TEMPLATE_MOTORHOME_RANGE,
        ]);
    }
}

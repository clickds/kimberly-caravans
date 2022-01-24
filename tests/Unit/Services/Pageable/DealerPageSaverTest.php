<?php

namespace Tests\Unit\Services\Pageable;

use App\Models\Dealer;
use App\Models\Site;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Page;
use App\Services\Pageable\DealerPageSaver;

class DealerPageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_dealer_listing_and_show_page()
    {
        $dealer = $this->createDealer();

        $saver = new DealerPageSaver($dealer, $dealer->site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $dealer->site->id,
            "name" => DealerPageSaver::DEALER_LISTING_PAGE_NAME,
            "template" => Page::TEMPLATE_DEALERS_LISTING,
        ]);

        $this->assertDatabaseHas('pages', [
            "site_id" => $dealer->site->id,
            "name" => $dealer->name,
            "pageable_type" => Dealer::class,
            "pageable_id" => $dealer->id,
            "template" => Page::TEMPLATE_DEALER_SHOW,
        ]);
    }

    public function test_updates_existing_page()
    {
        $dealer = $this->createDealer();
        $saver = new DealerPageSaver($dealer, $dealer->site);
        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $dealer->site->id,
            "name" => DealerPageSaver::DEALER_LISTING_PAGE_NAME,
            "template" => Page::TEMPLATE_DEALERS_LISTING,
        ]);

        $this->assertDatabaseHas('pages', [
            "site_id" => $dealer->site->id,
            "name" => $dealer->name,
            "pageable_type" => Dealer::class,
            "pageable_id" => $dealer->id,
            "template" => Page::TEMPLATE_DEALER_SHOW,
        ]);

        $dealer->name = 'An updated name';
        $dealer->save();
        $saver = new DealerPageSaver($dealer, $dealer->site);
        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $dealer->site->id,
            "name" => DealerPageSaver::DEALER_LISTING_PAGE_NAME,
            "template" => Page::TEMPLATE_DEALERS_LISTING,
        ]);

        $this->assertDatabaseHas('pages', [
            "site_id" => $dealer->site->id,
            "name" => "An updated name",
            "pageable_type" => Dealer::class,
            "pageable_id" => $dealer->id,
            "template" => Page::TEMPLATE_DEALER_SHOW,
        ]);
    }

    private function createDealer(): Dealer
    {
        $site = factory(Site::class)->state('default')->create();

        $dealer = factory(Dealer::class)->make();

        $dealer->site()->associate($site)->save();

        return $dealer;
    }
}

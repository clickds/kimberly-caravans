<?php

namespace App\Services\Pageable;

use App\Models\Dealer;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Facades\Log;

final class DealerPageSaver
{
    public const DEALER_LISTING_PAGE_NAME = 'Dealers';

    /**
     * @var \App\Models\Site
     */
    private $site;
    /**
     * @var \App\Models\Dealer
     */
    private $dealer;

    public function __construct(Dealer $dealer, Site $site)
    {
        $this->dealer = $dealer;
        $this->site = $site;
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();

            $dealerListingPage = $this->findOrCreateDealerListingPage();

            $dealerPage = $this->findOrCreateDealerPage();

            $dealerPage->name = $this->dealer->name;

            $dealerPage->meta_title = $this->dealer->name;

            $dealerPage
                ->parent()
                ->associate($dealerListingPage)
                ->save();

            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();
        }
    }

    private function findOrCreateDealerListingPage(): Page
    {
        return Page::firstOrCreate(
            [
                'site_id' => $this->site->id,
                'template' => Page::TEMPLATE_DEALERS_LISTING,
            ],
            [
                'name' => self::DEALER_LISTING_PAGE_NAME,
                'meta_title' => self::DEALER_LISTING_PAGE_NAME,
            ],
        );
    }

    private function findOrCreateDealerPage(): Page
    {
        return $this->dealer->pages()->firstOrNew([
            'site_id' => $this->site->id,
            'template' => Page::TEMPLATE_DEALER_SHOW,
        ]);
    }
}

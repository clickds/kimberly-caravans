<?php

namespace App\Facades\StockItem;

use App\Facades\BasePage;
use Illuminate\Http\Request;
use App\Models\CaravanStockItem;
use App\Models\Dealer;
use App\Models\Form;
use App\Models\Page;
use App\Models\PopUp;
use App\Models\Site;
use App\Models\SpecialOffer;
use Illuminate\Database\Eloquent\Collection;

class CaravanPage extends BasePage
{
    private ?Page $warrantyPage;
    private ?Page $manufacturersWarrantyPage;
    private CaravanStockItem $stockItem;
    private array $dealers;
    private ?Form $partExchangeForm;
    private Collection $specialOfferPages;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);
        $stockItem = $page->pageable;
        $stockItem->load([
            'berths' => function ($query) {
                $query->orderBy('number', 'asc');
            },
        ]);
        $this->stockItem = $stockItem;
        $this->dealers = $this->fetchDealers();
        $this->warrantyPage = $this->fetchWarrantyPage($page->site);
        $this->manufacturersWarrantyPage = $this->fetchManufacturersWarrantyPage($page->site);
        $this->partExchangeForm = $this->fetchPartExchangeForm();
        $this->specialOfferPages = $this->fetchSpecialOfferPages($page->site, $stockItem);
    }

    public function getDealers(): array
    {
        return $this->dealers;
    }

    public function comparisonPageExists(): bool
    {
        return Page::where('site_id', $this->getSite()->id)
            ->where('template', Page::TEMPLATE_CARAVAN_COMPARISON)->displayable()->exists();
    }

    public function getStockItem(): CaravanStockItem
    {
        return $this->stockItem;
    }

    public function getWarrantyPage(): ?Page
    {
        return $this->warrantyPage;
    }

    public function getManufacturersWarrantyPage(): ?Page
    {
        return $this->manufacturersWarrantyPage;
    }

    public function getPartExchangeForm(): ?Form
    {
        return $this->partExchangeForm;
    }

    public function getSpecialOfferPages(): Collection
    {
        return $this->specialOfferPages;
    }

    public function firstEligiblePopUp(): ?PopUp
    {
        $pagePopUp = $this->fetchEligiblePopUpForPage();
        $usedCaravanPagePopUp = $this->fetchEligiblePopUpForUsedCaravanPages();
        $allPagesPopUp = $this->fetchEligiblePopUpForAllPages();

        if (!is_null($pagePopUp)) {
            return $pagePopUp;
        }

        if (!is_null($usedCaravanPagePopUp)) {
            return $usedCaravanPagePopUp;
        }

        return $allPagesPopUp;
    }

    private function fetchDealers(): array
    {
        return Dealer::branch()
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->where('site_id', $this->getSite()->id)
            ->get()
            ->toArray();
    }

    private function fetchEligiblePopUpForUsedCaravanPages(): ?PopUp
    {
        // Stock items can be new, so return if that is the case.
        if (!$this->stockItem->isUsed()) {
            return null;
        }

        $dismissedPopUpIds = $this->fetchDismissedPopUpIds();

        $query = PopUp::displayable()->where('appears_on_used_caravan_pages', true);

        if (!empty($dismissedPopUpIds)) {
            $query->whereNotIn('id', $dismissedPopUpIds);
        }

        return $query->first();
    }

    private function fetchSpecialOfferPages(Site $site, CaravanStockItem $stockItem): Collection
    {
        $specialOfferIds = $stockItem->specialOffers()
            ->toBase()
            ->pluck('id')
            ->toArray();

        return Page::with('parent', 'pageable')
            ->template(Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW)
            ->where('pageable_type', SpecialOffer::class)
            ->whereIn('pageable_id', $specialOfferIds)
            ->join('special_offers', 'special_offers.id', '=', 'pages.pageable_id')
            ->distinct()
            ->orderBy('special_offers.position', 'asc')
            ->forSite($site)->displayable()->get();
    }

    private function fetchWarrantyPage(Site $site): ?Page
    {
        return Page::forSite($site)->variety(Page::VARIETY_CARAMARQ)
            ->displayable()->first();
    }

    private function fetchManufacturersWarrantyPage(Site $site): ?Page
    {
        return Page::forSite($site)
            ->variety(Page::VARIETY_MANUFACTURERS_WARRANTY)
            ->displayable()
            ->first();
    }

    private function fetchPartExchangeForm(): ?Form
    {
        return Form::where('type', Form::TYPE_PART_EXCHANGE)->first();
    }
}

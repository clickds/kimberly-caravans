<?php

namespace App\Facades;

use Illuminate\Http\Request;
use App\Models\Page;

class CaravanComparisonPage extends BasePage
{
    private string $caravanStockSearchPageUrl;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->caravanStockSearchPageUrl = $this->fetchCaravanStockSearchPageUrl();
    }

    public function getCaravanStockSearchPageUrl(): string
    {
        return $this->caravanStockSearchPageUrl;
    }

    private function fetchCaravanStockSearchPageUrl(): string
    {
        $searchPage = Page::forSite($this->getSite())
            ->displayable()
            ->where('template', Page::TEMPLATE_CARAVAN_SEARCH)
            ->first();

        return is_null($searchPage) ? '' : pageLink($searchPage);
    }
}

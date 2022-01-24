<?php

namespace App\Facades;

use Illuminate\Http\Request;
use App\Models\Page;

class MotorhomeComparisonPage extends BasePage
{
    private string $motorhomeStockSearchPageUrl;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->motorhomeStockSearchPageUrl = $this->fetchMotorhomeStockSearchPageUrl();
    }

    public function getMotorhomeStockSearchPageUrl(): string
    {
        return $this->motorhomeStockSearchPageUrl;
    }

    private function fetchMotorhomeStockSearchPageUrl(): string
    {
        $searchPage = Page::forSite($this->getSite())
            ->displayable()
            ->where('template', Page::TEMPLATE_MOTORHOME_SEARCH)
            ->first();

        return is_null($searchPage) ? '' : pageLink($searchPage);
    }
}

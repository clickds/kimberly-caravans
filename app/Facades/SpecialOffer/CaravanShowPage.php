<?php

namespace App\Facades\SpecialOffer;

use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Facades\CaravanSearchPage;
use App\Models\SpecialOffer;
use App\Models\Page;

class CaravanShowPage extends CaravanSearchPage
{
    private SpecialOffer $specialOffer;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);
        $specialOffer = $page->pageable;
        $this->specialOffer = $specialOffer;
    }

    public function getSpecialOffer(): SpecialOffer
    {
        return $this->specialOffer;
    }

    public function getSearchUrl(): string
    {
        return route('api.caravan-stock-items.special-offer.search', $this->getSpecialOffer());
    }
}

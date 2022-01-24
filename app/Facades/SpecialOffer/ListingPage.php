<?php

namespace App\Facades\SpecialOffer;

use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\SpecialOffer;
use App\Models\Page;
use Illuminate\Support\Collection;

class ListingPage extends BasePage
{
    private Collection $specialOffers;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);
        $this->specialOffers = $this->fetchSpecialOffers();
    }

    public function getSpecialOffers(): Collection
    {
        return $this->specialOffers;
    }

    private function fetchSpecialOffers(): Collection
    {
        $site = $this->getSite();
        return SpecialOffer::forSite($site)->displayable()->orderedByPosition()->with([
            'pages' => function ($query) {
                $query->with('parent')->displayable();
            }
        ])->get();
    }
}

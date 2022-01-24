<?php

namespace App\Facades\Dealer;

use App\Models\Dealer;
use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\Page;
use App\QueryBuilders\AbstractStockItemQueryBuilder;

class ShowPage extends BasePage
{
    /**
     * @var Dealer
     */
    private $dealer;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->dealer = $page->pageable;
    }

    public function getDealer(): Dealer
    {
        return $this->dealer;
    }

    public function getCaravanManagersSpecialsSearchUrl(): string
    {
        return route('api.caravan-stock-items.managers-specials.search');
    }

    public function getMotorhomeManagersSpecialsSearchUrl(): string
    {
        return route('api.motorhome-stock-items.managers-specials.search');
    }
}

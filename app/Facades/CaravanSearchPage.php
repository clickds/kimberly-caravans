<?php

namespace App\Facades;

use App\Models\Caravan;
use App\Models\Layout;
use App\Models\Manufacturer;
use App\QueryBuilders\CaravanStockItemQueryBuilder;
use App\Models\StockSearchLink;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use Illuminate\Database\Eloquent\Collection;

class CaravanSearchPage extends BasePage
{
    /**
     * Used on the search by berths panel
     */
    public const BERTH_OPTIONS = [
        [
            'displayName' => '2 Berths',
            'shortDisplayName' => '2',
            'min' => 2,
            'max' => 2,
        ],
        [
            'displayName' => '3 Berths',
            'shortDisplayName' => '3',
            'min' => 3,
            'max' => 3,
        ],
        [
            'displayName' => '4 Berths',
            'shortDisplayName' => '4',
            'min' => 4,
            'max' => 4,
        ],
        [
            'displayName' => '5 Berths',
            'shortDisplayName' => '5',
            'min' => 5,
            'max' => 5,
        ],
        [
            'displayName' => '6 Berths',
            'shortDisplayName' => '6',
            'min' => 6,
            'max' => 6,
        ],
        [
            'displayName' => '6 Berths + ',
            'shortDisplayName' => '6+',
            'min' => 6,
        ],
    ];

    public function getFilters(): array
    {
        $statuses = [];
        foreach (CaravanStockItemQueryBuilder::STATUSES as $status) {
            $statuses[] = ['name' => $status];
        }

        $manufacturers = [];
        $manufacturers = Manufacturer::orderBy('name', 'asc')
            ->whereHas('caravanStockItems', function ($query) {
                $query->live();
            })
            ->get()
            ->each(function (Manufacturer $manufacturer) use (&$manufacturers) {
                $manufacturers[] = ['name' => $manufacturer->name];
            });

        $axles = [];
        foreach (Caravan::AXLES as $axle) {
            $axles[] = ['name' => $axle];
        }

        $layouts = [];
        $layouts = Layout::has('caravanStockItems')->get()->each(function (Layout $layout) use (&$layouts) {
            $layouts[] = ['name' => $layout->name];
        });

        return [
            [
                'name' => AbstractStockItemQueryBuilder::FILTER_STATUS,
                'displayName' => 'Status',
                'type' => 'options',
                'options' => $statuses,
            ],
            [
                'name' => AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
                'displayName' => 'Manufacturer',
                'type' => 'options',
                'options' => $manufacturers,
            ],
            [
                'name' => 'axles',
                'displayName' => 'Axles',
                'type' => 'options',
                'options' => $axles,
            ],
            [
                'name' => AbstractStockItemQueryBuilder::FILTER_LAYOUT,
                'displayName' => 'Layout',
                'type' => 'options',
                'options' => $layouts,
            ],
            [
                'name' => 'length',
                'displayName' => 'Length (m)',
                'type' => 'range',
                'min' => 5,
                'max' => 7.5,
                'increments' => 0.5,
            ],
            [
                'name' => 'mtplm',
                'displayName' => 'MTPLM (kg)',
                'type' => 'range',
                'min' => 500,
                'max' => 2500,
                'increments' => 500,
            ],
            [
                'name' => 'price',
                'displayName' => 'Price (£)',
                'type' => 'range',
                'min' => 10000,
                'max' => 20000,
                'increments' => 5000,
            ],
            [
                'name' => 'berths',
                'displayName' => 'Berths',
                'type' => 'range',
                'min' => 2,
                'max' => 6,
                'increments' => 1,
            ],
        ];
    }

    public function getStockSearchLinks(): Collection
    {
        return StockSearchLink::type(StockSearchLink::TYPE_CARAVAN)
            ->forSite($this->getSite())
            ->whereHas('image')
            ->whereHas('mobileImage')
            ->with('image', 'mobileImage', 'page.parent')
            ->limit(2)
            ->get();
    }

    public function getSearchUrl(): string
    {
        return route('api.caravan-stock-items.search');
    }
}

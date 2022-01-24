<?php

namespace App\Facades;

use App\Models\Layout;
use App\Models\Manufacturer;
use App\Models\MotorhomeStockItem;
use App\QueryBuilders\MotorhomeStockItemQueryBuilder;
use App\Models\Seat;
use App\Models\StockSearchLink;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use Illuminate\Database\Eloquent\Collection;

class MotorhomeSearchPage extends BasePage
{
    /**
     * Used on the search page and search by berths panel
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
        $conversions = [];
        MotorhomeStockItem::select('conversion')->distinct()->pluck('conversion')
            ->map(function ($conversion) use (&$conversions) {
                $conversions[] = ['name' => $conversion];
            });

        $statuses = [];
        foreach (MotorhomeStockItemQueryBuilder::STATUSES as $status) {
            $statuses[] = ['name' => $status];
        }

        $manufacturers = [];
        $manufacturers = Manufacturer::orderBy('name', 'asc')
            ->whereHas('motorhomeStockItems', function ($query) {
                $query->live();
            })
            ->get()
            ->each(function ($manufacturer) use (&$manufacturers) {
                $manufacturers[] = ['name' => $manufacturer->name];
            });

        $travelSeats = [];
        Seat::join('motorhome_stock_item_seat', 'motorhome_stock_item_seat.seat_id', '=', 'seats.id')
            ->orderBy('number', 'asc')
            ->distinct()->pluck('number')
            ->map(function ($number) use (&$travelSeats) {
                $travelSeats[] = ['name' => $number];
            });

        $transmissions = [];
        MotorhomeStockItem::select('transmission')->distinct()->pluck('transmission')
            ->map(function ($name) use (&$transmissions) {
                $transmissions[] = ['name' => $name];
            });

        $chassis = [];
        MotorhomeStockItem::select('chassis_manufacturer')->distinct()->pluck('chassis_manufacturer')
            ->map(function ($name) use (&$chassis) {
                $chassis[] = ['name' => $name];
            });

        $layouts = [];
        $layouts = Layout::has('motorhomeStockItems')->get()->each(function (Layout $layout) use (&$layouts) {
            $layouts[] = ['name' => $layout->name];
        });

        return [
            [
                'name' => 'conversion',
                'displayName' => 'Conversion',
                'type' => 'options',
                'options' => $conversions,
            ],
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
                'min' => 3500,
                'max' => 4000,
                'increments' => 100,
            ],
            [
                'name' => 'price',
                'displayName' => 'Price (£)',
                'type' => 'range',
                'min' => 25000,
                'max' => 60000,
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
            [
                'name' => 'designated_seats',
                'displayName' => 'Travel Seats',
                'type' => 'options',
                'options' => $travelSeats,
            ],
            [
                'name' => 'transmission',
                'displayName' => 'Transmission',
                'type' => 'options',
                'options' => $transmissions,
            ],
            [
                'name' => 'chassis_manufacturer',
                'displayName' => 'Chassis',
                'type' => 'options',
                'options' => $chassis,
            ],
        ];
    }

    public function getStockSearchLinks(): Collection
    {
        return StockSearchLink::type(StockSearchLink::TYPE_MOTORHOME)
            ->forSite($this->getSite())
            ->whereHas('image')
            ->whereHas('mobileImage')
            ->with('image', 'mobileImage', 'page.parent')
            ->limit(2)
            ->get();
    }

    public function getSearchUrl(): string
    {
        return route('api.motorhome-stock-items.search');
    }
}

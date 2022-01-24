<?php

use App\Models\CaravanStockItem;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Database\Seeder;

class StockItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultSite = Site::where('is_default', true)->where('has_stock', true)->first();
        if (is_null($defaultSite)) {
            $defaultSite = factory(Site::class)->states(['default', 'has-stock'])->create();
        }

        // Note events on the stock items will create the pages
        $motorhomeStockItem = factory(MotorhomeStockItem::class)->create();
        $caravanStockItem = factory(CaravanStockItem::class)->create();
    }
}

<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CaravanSaved;
use App\Services\Caravan\StockItemSaver;

class SaveCaravanStockItem
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CaravanSaved  $event
     * @return void
     */
    public function handle(CaravanSaved $event)
    {
        $saver = new StockItemSaver($event->getCaravan());
        $saver->call();
    }
}

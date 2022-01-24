<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\MotorhomeSaved;
use App\Services\Motorhome\StockItemSaver;

class SaveMotorhomeStockItem
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
     * @param  MotorhomeSaved  $event
     * @return void
     */
    public function handle(MotorhomeSaved $event)
    {
        $saver = new StockItemSaver($event->getMotorhome());
        $saver->call();
    }
}

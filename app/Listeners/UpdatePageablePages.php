<?php

namespace App\Listeners;

use App\Events\PageableUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CaravanStockItem;
use App\Models\MotorhomeStockItem;
use App\Services\Pageable\StockItemPagesUpdater;

/**
 * Used when a pageable is saved
 */
class UpdatePageablePages
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
     * @param  PageableUpdated  $event
     * @return void
     */
    public function handle(PageableUpdated $event): void
    {
        $pageable = $event->getPageable();
        switch (get_class($pageable)) {
            case CaravanStockItem::class:
            case MotorhomeStockItem::class:
                $this->updateStockItemPages($pageable);
                break;
            default:
                break;
        }
    }

    /**
     * @param CaravanStockItem|MotorhomeStockItem $stockItem
     */
    private function updateStockItemPages($stockItem): void
    {
        $updater = new StockItemPagesUpdater($stockItem);
        $updater->call();
    }
}

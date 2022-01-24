<?php

namespace App\Services\Pageable;

use App\Models\CaravanStockItem;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;

class StockItemPagesUpdater
{
    /**
     * @var \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem
     */
    private $stockItem;

    /**
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\Site>
     */
    private $sites;

    /**
     * @param \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem $stockItem
     */
    public function __construct($stockItem)
    {
        $this->stockItem = $stockItem;
        $this->sites = Site::hasStock()->get();
    }

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            foreach ($this->getSites() as $site) {
                $this->createSitePage($site);
            }
            DB::commit();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }

    private function createSitePage(Site $site): void
    {
        $page = $this->getStockItem()->pages()->firstOrNew([
            'site_id' => $site->id,
        ]);
        $page->name = $this->calculatePageName();
        $page->meta_title = $this->calculatePageName();
        $page->template = $this->calculatePageTemplate();
        if ($page->isDirty('name')) {
            $page->slug = '';
        }
        $page->live = true;
        if ($this->getStockItem()->searchIndexShouldBeUpdated()) {
            $page->save();
        } else {
            Page::withoutSyncingToSearch(function () use ($page) {
                $page->save();
            });
        }
    }

    /**
     * @return \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem
     */
    private function getStockItem()
    {
        return $this->stockItem;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Site>
     */
    private function getSites(): Collection
    {
        return $this->sites;
    }

    private function calculatePageName(): string
    {
        $stockItem = $this->getStockItem();
        $parts = [
            $stockItem->manufacturerName(),
            $stockItem->model,
            $stockItem->unique_code,
        ];

        return implode(" ", $parts);
    }

    private function calculatePageTemplate(): string
    {
        switch (get_class($this->getStockItem())) {
            case CaravanStockItem::class:
                return Page::TEMPLATE_CARAVAN_STOCK_ITEM;
            default:
                return Page::TEMPLATE_MOTORHOME_STOCK_ITEM;
        }
    }
}

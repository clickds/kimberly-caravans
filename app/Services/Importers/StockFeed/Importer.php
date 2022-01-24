<?php

namespace App\Services\Importers\StockFeed;

use App\Models\CaravanStockItem;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use Illuminate\Support\Facades\Log;
use Throwable;

class Importer
{
    /**
     * @var \App\Services\Importers\StockFeed\Fetcher
     */
    private $fetcher;

    public function __construct(Fetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function call(): bool
    {
        try {
            $this->setExistingFeedItemsToNotDisplay();
            $feedResponse = $this->fetchFeedResponse();
            foreach ($feedResponse as $index => $feedItem) {
                $this->importFeedItem($feedItem);
            }
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }

    private function setExistingFeedItemsToNotDisplay(): void
    {
        CaravanStockItem::fromFeed()->update([
            'live' => false,
        ]);
        $feedCaravanStockItemIds = CaravanStockItem::fromFeed()->toBase()->pluck('id');
        Page::where('pageable_type', CaravanStockItem::class)
            ->whereIn('pageable_id', $feedCaravanStockItemIds)->update([
                'live' => false,
            ]);
        MotorhomeStockItem::fromFeed()->update([
            'live' => false,
        ]);
        $feedMotorhomeStockItemIds = MotorhomeStockItem::fromFeed()->toBase()->pluck('id');
        Page::where('pageable_type', MotorhomeStockItem::class)
            ->whereIn('pageable_id', $feedMotorhomeStockItemIds)->update([
                'live' => false,
            ]);
    }

    private function fetchFeedResponse(): array
    {
        return $this->getFetcher()->getFeed();
    }

    private function getFetcher(): Fetcher
    {
        return $this->fetcher;
    }

    private function importFeedItem(array $feedItem): void
    {
        switch ($feedItem["Category"]) {
            case 'Caravan':
                $this->importCaravan($feedItem);
                break;
            default:
                $this->importMotorHome($feedItem);
                break;
        }
    }

    private function importCaravan(array $feedItem): void
    {
        $importer = new CaravanCreator($feedItem, $this->getFetcher());
        $importer->call();
    }

    private function importMotorhome(array $feedItem): void
    {
        $importer = new MotorhomeCreator($feedItem, $this->getFetcher());
        $importer->call();
    }
}

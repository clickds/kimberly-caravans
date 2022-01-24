<?php

namespace App\Services\Caravan;

use App\Models\Caravan;
use App\Models\CaravanRange;
use App\Models\CaravanStockItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StockItemSaver
{
    /**
     * @var \App\Models\Caravan $caravan
     */
    private $caravan;
    /**
     * @var \App\Models\CaravanRange $caravanRange
     */
    private $caravanRange;

    public function __construct(Caravan $caravan)
    {
        $this->caravan = $caravan;
        $this->caravanRange = $this->fetchCaravanRange($caravan);
    }

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $stockItem = $this->saveStockItem();
            $caravan = $this->getCaravan();
            $stockItem->berths()->sync($caravan->berths()->pluck('id'));

            DB::commit();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollback();
            return false;
        }
    }

    private function saveStockItem(): CaravanStockItem
    {
        $caravan = $this->getCaravan();
        $price = $this->getPrice();
        $recommendedPrice = $this->getRecommendedPrice();
        $stockItem = $caravan->stockItem()->firstOrNew([]);
        $attributes = Arr::only($caravan->getAttributes(), $this->attributesToCopyFromCaravan());
        $attributes = array_merge($this->defaultAttributes(), $attributes);
        $attributes['price'] = $price;
        $attributes['recommended_price'] = $recommendedPrice;
        $attributes['reduced_price_start_date'] = $this->getReducedPriceStartDate(
            $price,
            $recommendedPrice,
            $stockItem
        );
        $stockItem->fill($attributes);
        $stockItem->save();
        return $stockItem;
    }

    private function defaultAttributes(): array
    {
        return [
            'condition' => CaravanStockItem::NEW_CONDITION,
            'model' => $this->getCaravan()->name,
            'manufacturer_id' => $this->getCaravanRange()->manufacturer_id,
            'source' => CaravanStockItem::NEW_PRODUCT_SOURCE,
            'number_of_owners' => 0,
        ];
    }

    private function attributesToCopyFromCaravan(): array
    {
        return [
            'layout_id',
            'axles',
            'width',
            'height',
            'live',
            'length',
            'mro',
            'mtplm',
            'payload',
            'berths',
            'year',
            'description',
            'exclusive',
        ];
    }

    private function getCaravan(): Caravan
    {
        return $this->caravan;
    }

    private function getCaravanRange(): CaravanRange
    {
        return $this->caravanRange;
    }

    private function getPrice(): ?float
    {
        $site = $this->getCaravan()->sites()->hasStock()->first();

        if (!$site) {
            return null;
        }

        return $site->pivot->price;
    }

    private function getRecommendedPrice(): ?float
    {
        $site = $this->getCaravan()->sites()->hasStock()->first();

        if (!$site) {
            return null;
        }

        return $site->pivot->recommended_price;
    }

    private function getReducedPriceStartDate(
        ?float $price,
        ?float $recommendedPrice,
        CaravanStockItem $stockItem
    ): ?Carbon {
        if (is_null($price)) {
            return null;
        }

        if (is_null($recommendedPrice)) {
            return null;
        }

        if ($price < $recommendedPrice) {
            return null;
        }

        return $stockItem->reduced_price_start_date
            ? Carbon::parse($stockItem->reduced_price_start_date)
            : Carbon::today();
    }

    private function fetchCaravanRange(Caravan $caravan): CaravanRange
    {
        $range = $caravan->caravanRange;
        if (is_null($range)) {
            throw new Exception("Caravan must have a caravan range");
        }
        return $range;
    }
}

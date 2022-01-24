<?php

namespace App\Services\Motorhome;

use App\Models\Motorhome;
use App\Models\MotorhomeRange;
use App\Models\MotorhomeStockItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StockItemSaver
{
    /**
     * @var \App\Models\Motorhome
     */
    private $motorhome;
    /**
     * @var \App\Models\MotorhomeRange
     */
    private $motorhomeRange;

    public function __construct(Motorhome $motorhome)
    {
        $this->motorhome = $motorhome;
        $this->motorhomeRange = $this->fetchMotorhomeRange($motorhome);
    }

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $stockItem = $this->saveStockItem();
            $motorhome = $this->getMotorhome();
            $stockItem->berths()->sync($motorhome->berths()->pluck('id'));
            $stockItem->seats()->sync($motorhome->seats()->pluck('id'));

            DB::commit();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollback();
            return false;
        }
    }

    private function saveStockItem(): MotorhomeStockItem
    {
        $motorhome = $this->getMotorhome();
        $price = $this->getPrice();
        $recommendedPrice = $this->getRecommendedPrice();
        $stockItem = $motorhome->stockItem()->firstOrNew([]);
        $attributes = Arr::only($motorhome->getAttributes(), $this->attributesToCopyFromMotorhome());
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
            'condition' => MotorhomeStockItem::NEW_CONDITION,
            'model' => $this->getMotorhome()->name,
            'manufacturer_id' => $this->getMotorhomeRange()->manufacturer_id,
            'source' => MotorhomeStockItem::NEW_PRODUCT_SOURCE,
            'mileage' => 0,
            'number_of_owners' => 0,
        ];
    }

    private function attributesToCopyFromMotorhome(): array
    {
        return [
            'conversion',
            'layout_id',
            'width',
            'height',
            'live',
            'length',
            'mro',
            'mtplm',
            'payload',
            'berths',
            'engine_size',
            'engine_power',
            'designated_seats',
            'transmission',
            'chassis_manufacturer',
            'fuel',
            'year',
            'description',
            'exclusive',
        ];
    }

    private function getMotorhome(): Motorhome
    {
        return $this->motorhome;
    }

    private function getMotorhomeRange(): MotorhomeRange
    {
        return $this->motorhomeRange;
    }

    private function getPrice(): ?float
    {
        $site = $this->getMotorhome()->sites()->hasStock()->first();

        if (!$site) {
            return null;
        }

        return $site->pivot->price;
    }

    private function getRecommendedPrice(): ?float
    {
        $site = $this->getMotorhome()->sites()->hasStock()->first();

        if (!$site) {
            return null;
        }

        return $site->pivot->recommended_price;
    }

    private function getReducedPriceStartDate(
        ?float $price,
        ?float $recommendedPrice,
        MotorhomeStockItem $stockItem
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

    private function fetchMotorhomeRange(Motorhome $motorhome): MotorhomeRange
    {
        $range = $motorhome->motorhomeRange;
        if (is_null($range)) {
            throw new Exception("Motorhome needs a motorhome range");
        }
        return $range;
    }
}

<?php

namespace App\Models\Traits;

use App\Models\Interfaces\StockItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait IsStock
{
    public function scopeEligibleForStockSearch(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->live()->newProduct();
        })->orWhere(function ($query) {
            $query->live()->fromFeed()->used();
        })->orWhere(function ($query) {
            $query->live()->fromFeed()->new()->hasSpecialOffer();
        });
    }

    public function scopeFromFeed(Builder $query): Builder
    {
        return $query->where('source', StockItem::FEED_SOURCE);
    }

    public function scopeNewProduct(Builder $query): Builder
    {
        return $query->where('source', StockItem::NEW_PRODUCT_SOURCE);
    }

    public function scopeLive(Builder $query, bool $value = true): Builder
    {
        return $query->where('live', $value);
    }

    public function scopeExclusive(Builder $query, bool $value = true): Builder
    {
        return $query->where('exclusive', $value);
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('condition', StockItem::NEW_CONDITION);
    }

    public function scopeUsed(Builder $query): Builder
    {
        return $query->where('condition', StockItem::USED_CONDITION);
    }

    public function scopeNewArrival(Builder $query, int $days = 14): Builder
    {
        $dateFrom = Carbon::today()->subDays($days);

        return $query->where(function ($query) use ($dateFrom) {
            $query->where('delivery_date', '>=', $dateFrom)
                ->where('delivery_date', '<=', Carbon::today());
        });
    }

    public function scopeReducedPriceRecentlyStarted(Builder $query, int $days = 14): Builder
    {
        $dateFrom = Carbon::today()->subDays($days);

        return $query->where(function ($query) use ($dateFrom) {
            $query->where('reduced_price_start_date', '>=', $dateFrom)
                ->where('reduced_price_start_date', '<=', Carbon::today());
        });
    }

    public function scopeHasSpecialOffer(Builder $query): Builder
    {
        return $query->whereHas('specialOffers', function (Builder $query) {
            return $query->live()->notExpired()->published();
        });
    }

    public function scopeHasRecentlyCreatedOffer(Builder $query, int $days = 14): Builder
    {
        $dateFrom = Carbon::today()->subDays($days);

        return $query->whereHas('specialOffers', function (Builder $query) use ($dateFrom) {
            return $query->live()->notExpired()->where(function (Builder $query) use ($dateFrom) {
                $query->where(function (Builder $query) use ($dateFrom) {
                    $query->where('published_at', '!=', null)
                        ->where('published_at', '>=', $dateFrom)
                        ->where('published_at', '<=', Carbon::today());
                })->orWhere(function (Builder $query) use ($dateFrom) {
                    $query->where('published_at', '=', null)
                        ->where('created_at', '>=', $dateFrom)
                        ->where('created_at', '<=', Carbon::today());
                });
            });
        });
    }

    public function scopeOrderByDeliveryDate(Builder $query, string $orderDirection = 'asc'): Builder
    {
        return $query->orderBy('delivery_date', $orderDirection);
    }

    public function scopeOnSale(Builder $query): Builder
    {
        return $query->whereNotNull('price')
            ->whereRaw('price < recommended_price');
    }

    public function scopeManagersSpecial(Builder $query, bool $value = true): Builder
    {
        return $query->where('managers_special', $value);
    }

    /**
     * Used for displaying the warranty, lets assume it's old if the registration
     * date from the feed is null
     */
    public function agedOverYears(int $years): bool
    {
        // If it's not from the feed it's a new model
        if (!$this->hasFeedSource()) {
            return false;
        }
        if (is_null($this->registration_date)) {
            return true;
        }
        $yearsAgo = Carbon::now()->subYears($years);

        return $this->registration_date->lessThanOrEqualTo($yearsAgo);
    }

    public function hasFeedSource(): bool
    {
        if ($this->source === StockItem::FEED_SOURCE) {
            return true;
        }
        return false;
    }

    public function isOnSale(): bool
    {
        if (is_null($this->recommended_price) || is_null($this->price)) {
            return false;
        }
        if ($this->price < $this->recommended_price) {
            return true;
        }
        return false;
    }

    public function priceReduction(): float
    {
        if (is_null($this->recommended_price) || is_null($this->price)) {
            return 0.0;
        }
        return $this->recommended_price - $this->price;
    }

    public function isUsed(): bool
    {
        return $this->condition === StockItem::USED_CONDITION;
    }

    public function isNew(): bool
    {
        return $this->condition === StockItem::NEW_CONDITION;
    }

    public function isManagersSpecial(): bool
    {
        return true === $this->managers_special;
    }

    abstract public function modelImages(): Collection;

    abstract public function floorplanImages(): Collection;

    abstract public function searchIndexShouldBeUpdated(): bool;
}

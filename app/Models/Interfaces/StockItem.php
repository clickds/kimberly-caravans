<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Site;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface StockItem
{
    public const TYPE_CARAVAN = "caravan";
    public const TYPE_MOTORHOME = "motorhome";

    public const FEED_SOURCE = "Feed";
    public const NEW_PRODUCT_SOURCE = "New Product";

    public const SOURCES = [
        self::FEED_SOURCE,
        self::NEW_PRODUCT_SOURCE,
    ];

    public const NEW_CONDITION = 'New';
    public const USED_CONDITION = 'Used';

    public const CONDITIONS = [
        self::NEW_CONDITION,
        self::USED_CONDITION,
    ];

    public function scopeOnSale(Builder $query): Builder;

    public function modelImages(): Collection;

    public function floorplanImages(): Collection;
    public function feedStockItemVideo(): MorphOne;

    public function isUsed(): bool;
    public function searchIndexShouldBeUpdated(): bool;
}

<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Liveable
{
    public function scopeLive(Builder $query, bool $live = true): Builder
    {
        $tableName = (new static)->getTable();
        return $query->where("{$tableName}.live", $live);
    }

    public function isLive(): bool
    {
        return $this->live;
    }
}

<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Expirable
{
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $tableName = (new static)->getTable();
            $query->whereNull("{$tableName}.expired_at")
                ->orWhere("{$tableName}.expired_at", '>=', Carbon::now());
        });
    }

    public function hasExpired(): bool
    {
        return !is_null($this->expired_at) && Carbon::now()->lessThanOrEqualTo($this->expired_at);
    }

    /**
     * @param mixed $value
     */
    public function getExpiredAtAttribute($value): ?Carbon
    {
        if (is_null($value)) {
            return $value;
        }
        return Carbon::parse($value);
    }

    public function getExpiredAtAsIso8601String(): string
    {
        $expiredAt = $this->expired_at;
        if (is_null($expiredAt)) {
            return "";
        }
        return $expiredAt->toIso8601String();
    }

    public function hasExpiredAtDate(): bool
    {
        return null !== $this->expired_at;
    }
}

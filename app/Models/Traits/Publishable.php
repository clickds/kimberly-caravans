<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $tableName = (new static)->getTable();
            $query->whereNull("{$tableName}.published_at")
                ->orWhere("{$tableName}.published_at", '<=', Carbon::now());
        });
    }

    public function scopePublishedRecently(Builder $query, int $days = 14): Builder
    {
        $dateFrom = Carbon::today()->subDays($days);

        return $query->where(function ($query) use ($dateFrom) {
            $query->where('published_at', '>=', $dateFrom)
                ->where('published_at', '<=', Carbon::today());
        });
    }

    public function isPublished(): bool
    {
        return is_null($this->published_at) || Carbon::now()->greaterThanOrEqualTo($this->published_at);
    }

    /**
     * @param mixed $value
     */
    public function getPublishedAtAttribute($value): ?Carbon
    {
        if (is_null($value)) {
            return $value;
        }
        return Carbon::parse($value);
    }

    public function getPublishedAtAsIso8601String(): string
    {
        $publishedAt = $this->published_at;
        if (is_null($publishedAt)) {
            return "";
        }
        return $publishedAt->toIso8601String();
    }

    public function hasPublishedAtDate(): bool
    {
        return null !== $this->published_at;
    }

    public function getSluggablePublishedAtAttribute(): string
    {
        $publishedAt = $this->published_at;
        if (is_null($publishedAt)) {
            return "";
        }
        return $publishedAt->format("d-m-Y");
    }
}

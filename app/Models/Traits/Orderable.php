<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * For easy ordering in the admin
 */
trait Orderable
{
    public function scopeOrderable(Builder $query, string $order): Builder
    {
        list('column' => $column, 'direction' => $direction) = $this->determineOrderFromString($order);

        return $query->orderBy($column, $direction);
    }

    private function determineOrderFromString(string $order): array
    {
        $parts = explode('_', $order);
        $direction = array_pop($parts);
        return [
            'column' => implode('_', $parts),
            'direction' => $direction,
        ];
    }
}

<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait RetrievesFilters
{
    /**
     * @var string
     */
    public static $filterParameterKey = 'filter';

    private function getFilters(Request $request): array
    {
        return $request->get(static::$filterParameterKey, []);
    }
}

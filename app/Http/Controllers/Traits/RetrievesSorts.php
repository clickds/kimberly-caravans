<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait RetrievesSorts
{
    /**
     * @var string
     */
    public static $sortParameterKey = 'sort';

    private function getSorts(Request $request): array
    {
        return $request->get(static::$sortParameterKey, []);
    }
}

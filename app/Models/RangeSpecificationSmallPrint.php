<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RangeSpecificationSmallPrint extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'content',
        'name',
        'position',
        'site_id',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'position' => 0,
    ];

    public function vehicleRange(): MorphTo
    {
        return $this->morphTo();
    }
}

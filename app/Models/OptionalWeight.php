<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionalWeight extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
    ];

    public function motorhome(): BelongsTo
    {
        return $this->belongsTo(Motorhome::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class BedSize extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'bed_description_id',
        'details',
    ];

    public function vehicle(): Relation
    {
        return $this->morphTo();
    }

    public function bedDescription(): Relation
    {
        return $this->belongsTo(BedDescription::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jdexx\EloquentRansack\Ransackable;

class BedDescription extends Model
{
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}

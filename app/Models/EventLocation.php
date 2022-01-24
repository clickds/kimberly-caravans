<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jdexx\EloquentRansack\Ransackable;

class EventLocation extends Model
{
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'town',
        'county',
        'postcode',
        'phone',
        'fax',
        'latitude',
        'longitude',
    ];
}

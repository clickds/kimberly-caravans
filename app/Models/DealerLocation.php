<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property float $distance_miles
 */
class DealerLocation extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'opening_hours',
        'line_1',
        'line_2',
        'town',
        'county',
        'postcode',
        'phone',
        'fax',
        'sat_nav_code',
        'google_maps_url',
        'latitude',
        'longitude',
    ];

    public function dealer(): Relation
    {
        return $this->belongsTo(Dealer::class);
    }

    public function scopeSelectDistanceToInMiles(Builder $query, array $coordinates): Builder
    {
        if (!$query->getQuery()->columns) {
            $query->select('*');
        }

        return $query->selectRaw("ST_Distance_Sphere(
            Point(longitude, latitude),
            Point(?, ?)
        ) * .000621371192 as distance_miles", $coordinates);
    }
}

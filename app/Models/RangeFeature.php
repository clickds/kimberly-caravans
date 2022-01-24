<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Jdexx\EloquentRansack\Ransackable;

class RangeFeature extends Model
{
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'content',
        'key',
        'name',
        'position',
        'warranty',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'position' => 0,
    ];

    public function scopeForSite(Builder $query, Site $site): Builder
    {
        return $query->whereHas('sites', function ($query) use ($site) {
            $query->where('id', $site->id);
        });
    }

    public function vehicleRange(): MorphTo
    {
        return $this->morphTo();
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class);
    }
}

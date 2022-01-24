<?php

namespace App\Models;

use App\Models\Traits\Expirable;
use App\Models\Traits\Pageable;
use App\Models\Traits\Publishable;
use App\Presenters\DealerPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;

class Dealer extends Model implements HasPresenter
{
    use Pageable;
    use Publishable;
    use Expirable;
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'feed_location_code',
        'is_branch',
        'is_servicing_center',
        'can_view_motorhomes',
        'can_view_caravans',
        'site_id',
        'position',
        'video_embed_code',
        'facilities',
        'servicing_centre',
        'website_url',
        'website_link_text',
        'published_at',
        'expired_at',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(DealerLocation::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(DealerEmployee::class);
    }

    public function caravanRanges(): BelongsToMany
    {
        return $this->belongsToMany(CaravanRange::class, 'dealer_caravan_ranges');
    }

    public function motorhomeRanges(): BelongsToMany
    {
        return $this->belongsToMany(MotorhomeRange::class, 'dealer_motorhome_ranges');
    }

    public function scopeBranch(Builder $query): Builder
    {
        return $query->where('is_branch', true);
    }

    public function scopeFeedLocationCode(Builder $query, string $feedLocationCode): Builder
    {
        return $query->where('feed_location_code', $feedLocationCode);
    }

    public function sluggableSources(): array
    {
        return ['name'];
    }

    public function getPresenterClass(): string
    {
        return DealerPresenter::class;
    }
}

<?php

namespace App\Models;

use App\Models\Traits\Expirable;
use App\Models\Traits\Publishable;
use App\Presenters\BrochurePresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Brochure extends Model implements HasMedia, HasPresenter
{
    use Publishable;
    use Expirable;
    use InteractsWithMedia;
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'url',
        'title',
        'site_id',
        'group_id',
        'published_at',
        'expired_at',
    ];

    public function scopeForSite(Builder $query, int $siteId): Builder
    {
        return $query->where('site_id', $siteId);
    }

    public function scopeDisplayable(Builder $query): Builder
    {
        return $query->published()->notExpired();
    }

    public function site(): Relation
    {
        return $this->belongsTo(Site::class);
    }

    public function group(): Relation
    {
        return $this->belongsTo(BrochureGroup::class);
    }

    public function caravanRanges(): BelongsToMany
    {
        return $this->belongsToMany(CaravanRange::class, 'caravan_range_brochures');
    }

    public function motorhomeRanges(): BelongsToMany
    {
        return $this->belongsToMany(MotorhomeRange::class, 'motorhome_range_brochures');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('brochure_file')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)->height(100)
            ->performOnCollections('image');

        $this->addMediaConversion('index')
            ->fit(Manipulations::FIT_FILL, 120, 160)
            ->performOnCollections('image');
    }

    public function getPresenterClass(): string
    {
        return BrochurePresenter::class;
    }
}

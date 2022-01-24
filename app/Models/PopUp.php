<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Expirable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Jdexx\EloquentRansack\Ransackable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PopUp extends Model implements HasMedia
{
    use Expirable;
    use InteractsWithMedia;
    use Liveable;
    use Publishable;
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'expired_at',
        'external_url',
        'live',
        'name',
        'page_id',
        'published_at',
        'site_id',
        'appears_on_all_pages',
        'appears_on_new_motorhome_pages',
        'appears_on_new_caravan_pages',
        'appears_on_used_motorhome_pages',
        'appears_on_used_caravan_pages',
    ];

    public function scopeDisplayable(Builder $query): Builder
    {
        return $query->live()->published()->notExpired();
    }

    public function appearsOnPages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'pop_up_appears_on_pages', 'pop_up_id', 'page_id');
    }

    public function caravanRanges(): BelongsToMany
    {
        return $this->belongsToMany(CaravanRange::class);
    }

    public function motorhomeRanges(): BelongsToMany
    {
        return $this->belongsToMany(MotorhomeRange::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('mobileImage')->singleFile();
        $this->addMediaCollection('desktopImage')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)
            ->height(80);
    }
}

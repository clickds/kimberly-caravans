<?php

namespace App\Models;

use App\Models\Traits\Expirable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Pageable;
use App\Models\Traits\Publishable;
use App\Presenters\ArticlePresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia, HasPresenter
{
    use Pageable;
    use Publishable;
    use InteractsWithMedia;
    use Orderable;
    use Ransackable;
    use Liveable;
    use Expirable;

    public const TYPES = [
        'Both',
        'Caravan',
        'Motorhome',
    ];

    public const STYLE_NEWS = 'News';
    public const STYLE_TELL_US_YOUR_STORY = 'Tell Us Your Story';
    public const STYLE_BOTH = 'Both';

    public const STYLES = [
        self::STYLE_NEWS,
        self::STYLE_TELL_US_YOUR_STORY,
        self::STYLE_BOTH,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'date',
        'dealer_id',
        'excerpt',
        'published_at',
        'style',
        'title',
        'type',
        'live',
        'expired_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    public function articleCategories(): BelongsToMany
    {
        return $this->belongsToMany(ArticleCategory::class);
    }

    public function caravanRanges(): BelongsToMany
    {
        return $this->belongsToMany(CaravanRange::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function motorhomeRanges(): BelongsToMany
    {
        return $this->belongsToMany(MotorhomeRange::class);
    }

    public function scopeStatus(Builder $query, string $value): Builder
    {
        if ($value == 'draft') {
            return $query->whereNull('published_at');
        }
        if ($value == 'published') {
            return $query->where('published_at', '<=', Carbon::now());
        }
        if ($value == 'pending') {
            return $query->where('published_at', '>', Carbon::now());
        }
        return $query;
    }

    public function scopeStyles(Builder $query, array $styles): Builder
    {
        return $query->whereIn('style', $styles);
    }

    public function sluggableSources(): array
    {
        return [
            'title',
            'sluggable_date',
        ];
    }

    public function getSluggableDateAttribute(): string
    {
        $date = $this->date;
        if (is_null($date)) {
            return "";
        }
        return $date->format("d-m-Y");
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)->height(80);

        $this->addMediaConversion('responsiveIndex')
            ->width(400)
            ->height(200)
            ->fit(Manipulations::FIT_CONTAIN, 400, 200)
            ->performOnCollections('image')
            ->withResponsiveImages()
            ->keepOriginalImageFormat()
            ->nonQueued();

        $this->addMediaConversion('responsiveShow')
            ->width(640)
            ->height(640)
            ->fit(Manipulations::FIT_CONTAIN, 640, 640)
            ->performOnCollections('image')
            ->withResponsiveImages()
            ->keepOriginalImageFormat()
            ->nonQueued();
    }

    public function getPresenterClass(): string
    {
        return ArticlePresenter::class;
    }
}

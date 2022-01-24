<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Featureable;
use App\Models\Traits\Pageable;
use App\Models\Traits\Publishable;
use App\Presenters\VideoPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Video extends Model implements HasMedia, HasPresenter
{
    use Featureable;
    use Pageable;
    use Publishable;
    use InteractsWithMedia;
    use Ransackable;

    public const TYPE_CARAVAN = 'Caravan';
    public const TYPE_MOTORHOME = 'Motorhome';
    public const TYPE_BOTH = 'Both';

    public const VALID_TYPES = [
        self::TYPE_CARAVAN,
        self::TYPE_MOTORHOME,
        self::TYPE_BOTH,
    ];

    /**
     * @var array $fillable;
     */
    protected $fillable = [
        'dealer_id',
        'type',
        'embed_code',
        'excerpt',
        'published_at',
        'title',
    ];

    public function sluggableSources(): array
    {
        return [
            'title',
            'sluggable_published_at',
        ];
    }

    public function videoCategories(): BelongsToMany
    {
        return $this->belongsToMany(VideoCategory::class);
    }

    public function motorhomeRanges(): BelongsToMany
    {
        return $this->belongsToMany(MotorhomeRange::class, 'motorhome_range_videos');
    }

    public function caravanRanges(): BelongsToMany
    {
        return $this->belongsToMany(CaravanRange::class, 'caravan_range_videos');
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbStockSlider')
            ->fit(Manipulations::FIT_CONTAIN, 120, 120);

        $this->addMediaConversion('responsiveIndex')
            ->width(400)
            ->height(200)
            ->fit(Manipulations::FIT_MAX, 400, 200)
            ->performOnCollections('image')
            ->withResponsiveImages()->nonQueued();
    }

    public function embedCodeUrl(): string
    {
        if (empty($this->embed_code)) {
            return "";
        }
        if (preg_match('/src="([^"]+)"/', $this->embed_code, $match)) {
            return $match[1];
        }
        return "";
    }

    public function getPresenterClass(): string
    {
        return VideoPresenter::class;
    }
}

<?php

namespace App\Models;

use App\Models\Interfaces\HasThemedProperties;
use App\Models\Traits\Liveable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Pageable;
use App\Models\Traits\ThemedProperties;
use App\Presenters\MotorhomeRangePresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use InvalidArgumentException;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MotorhomeRange extends Model implements HasMedia, HasPresenter, HasThemedProperties
{
    use InteractsWithMedia;
    use Liveable;
    use Pageable;
    use Ransackable;
    use ThemedProperties;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'prepend_range_name_to_model_names',
        'overview',
        'position',
        'primary_theme_colour',
        'secondary_theme_colour',
        'live',
        'exclusive',
        'manufacturer_id',
    ];

    public const PRIMARY_THEME_COLOURS = HasThemedProperties::COLOURS;
    public const SECONDARY_THEME_COLOURS = HasThemedProperties::COLOURS;

    public const GALLERY_INTERIOR = 'interiorGallery';
    public const GALLERY_EXTERIOR = 'exteriorGallery';
    public const GALLERY_FEATURE = 'featureGallery';

    public const GALLERY_NAMES = [
        self::GALLERY_INTERIOR,
        self::GALLERY_EXTERIOR,
        self::GALLERY_FEATURE,
    ];

    public function features(): MorphMany
    {
        return $this->morphMany(RangeFeature::class, 'vehicle_range');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function motorhomes(): HasMany
    {
        return $this->hasMany(Motorhome::class);
    }

    public function motorhomeStockItems(): HasMany
    {
        return $this->hasMany(MotorhomeStockItem::class);
    }

    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'motorhome_range_review');
    }

    public function brochures(): BelongsToMany
    {
        return $this->belongsToMany(Brochure::class, 'motorhome_range_brochures');
    }

    public function specificationSmallPrints(): MorphMany
    {
        return $this->morphMany(RangeSpecificationSmallPrint::class, 'vehicle_range');
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'motorhome_range_videos');
    }

    public function dealers(): BelongsToMany
    {
        return $this->belongsToMany(Dealer::class, 'dealer_motorhome_ranges');
    }

    public function setPrimaryThemeColourAttribute(string $themeColour): void
    {
        if (!in_array($themeColour, array_keys(self::PRIMARY_THEME_COLOURS))) {
            throw new InvalidArgumentException('Invalid primary theme colour');
        }

        $this->attributes['primary_theme_colour'] = $themeColour;
    }

    public function setSecondaryThemeColourAttribute(string $themeColour): void
    {
        if (!in_array($themeColour, array_keys(self::SECONDARY_THEME_COLOURS))) {
            throw new InvalidArgumentException('Invalid secondary theme colour');
        }

        $this->attributes['secondary_theme_colour'] = $themeColour;
    }

    public function sluggableSources(): array
    {
        return [
            'name',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tabContentImage')->singleFile();
        $this->addMediaCollection('mainImage')->singleFile();
        $this->addMediaCollection('logo')->singleFile();
        foreach (static::GALLERY_NAMES as $collectionName) {
            $this->addMediaCollection($collectionName);
        }
    }

    public function tabContentImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', 'tabContentImage');
    }

    public function galleryImages(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->whereIn('collection_name', static::GALLERY_NAMES);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_FILL, 100, 100)
            ->crop(Manipulations::CROP_CENTER, 100, 100);

        $this->addMediaConversion('responsiveGallery')
            ->fit(Manipulations::FIT_FILL, 375, 375)
            ->crop(Manipulations::CROP_CENTER, 375, 375)
            ->withResponsiveImages()
            ->performOnCollections(...static::GALLERY_NAMES);

        $this->addMediaConversion('responsiveStockSlider')
            ->fit(Manipulations::FIT_FILL, 600, 450)
            ->crop(Manipulations::CROP_CENTER, 600, 450)
            ->withResponsiveImages()
            ->performOnCollections(...static::GALLERY_NAMES);

        $this->addMediaConversion('thumbStockSlider')
            ->fit(Manipulations::FIT_FILL, 120, 120)
            ->crop(Manipulations::CROP_CENTER, 120, 120)
            ->performOnCollections(...static::GALLERY_NAMES);

        $this->addMediaConversion('show')
            ->fit(Manipulations::FIT_FILL, 1980, 645)
            ->crop(Manipulations::CROP_CENTER, 1980, 645)
            ->performOnCollections('mainImage');

        $this->addMediaConversion('stockListing')
            ->fit(Manipulations::FIT_CROP, 350, 260);

        $this->addMediaConversion('show')
            ->width(375)
            ->height(375)
            ->keepOriginalImageFormat()
            ->fit(Manipulations::FIT_FILL, 375, 375)
            ->crop(Manipulations::CROP_CENTER, 375, 375)
            ->performOnCollections('tabContentImage')
            ->nonQueued();
    }

    public function getPresenterClass(): string
    {
        return MotorhomeRangePresenter::class;
    }
}

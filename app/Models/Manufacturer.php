<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Traits\Pageable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Jdexx\EloquentRansack\Ransackable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Manufacturer extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Pageable;
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'exclusive',
        'name',
        'motorhome_position',
        'caravan_position',
        'caravan_intro_text',
        'motorhome_intro_text',
        'link_directly_to_stock_search',
    ];

    protected $casts = [
        'link_directly_to_stock_search' => 'boolean',
    ];

    public function linkedToManufacturers(): BelongsToMany
    {
        return $this->belongsToMany(
            Manufacturer::class,
            'manufacturer_linked_manufacturers',
            'manufacturer_id',
            'linked_manufacturer_id'
        );
    }

    public function linkedByManufacturers(): BelongsToMany
    {
        return $this->belongsToMany(
            Manufacturer::class,
            'manufacturer_linked_manufacturers',
            'linked_manufacturer_id',
            'manufacturer_id'
        );
    }

    public function motorhomeRanges(): HasMany
    {
        return $this->hasMany(MotorhomeRange::class);
    }

    public function motorhomeStockItems(): HasMany
    {
        return $this->hasMany(MotorhomeStockItem::class);
    }

    public function caravanRanges(): HasMany
    {
        return $this->hasMany(CaravanRange::class);
    }

    public function caravanStockItems(): HasMany
    {
        return $this->hasMany(CaravanStockItem::class);
    }

    /**
     * Called when pages are created
     */
    public function sluggableSources(): array
    {
        return [
            'name',
        ];
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', 'logo');
    }

    public function caravanImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', 'caravanImage');
    }

    public function motorhomeImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', 'motorhomeImage');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('caravanImage')->singleFile();
        $this->addMediaCollection('motorhomeImage')->singleFile();
        $this->addMediaCollection('logo')->singleFile();
    }

    public function registerAllMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);

        $this->addMediaConversion('manufacturerSlider')
            ->fit(Manipulations::FIT_CONTAIN, 150, 150)
            ->keepOriginalImageFormat()
            ->performOnCollections('logo')
            ->nonQueued();

        $this->addMediaConversion('show')
            ->fit(Manipulations::FIT_MAX, 900, 600)
            ->keepOriginalImageFormat()
            ->performOnCollections('logo')
            ->nonQueued();

        $this->addMediaConversion('show')
            ->width(375)
            ->height(375)
            ->keepOriginalImageFormat()
            ->fit(Manipulations::FIT_FILL, 375, 375)
            ->crop(Manipulations::CROP_CENTER, 375, 375)
            ->performOnCollections('caravanImage', 'motorhomeImage')
            ->nonQueued();
    }
}

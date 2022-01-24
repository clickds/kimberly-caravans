<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use UnexpectedValueException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Interfaces\Vehicle;
use App\Models\Traits\Liveable;
use App\Presenters\CaravanPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Caravan extends Model implements HasMedia, Vehicle, HasPresenter
{
    use InteractsWithMedia;
    use Liveable;
    use Ransackable;

    public const AXLE_SINGLE = 'Single';
    public const AXLE_TWIN = 'Twin';

    public const AXLES = [
        self::AXLE_SINGLE,
        self::AXLE_TWIN,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'axles',
        'description',
        'exclusive',
        'height_includes_aerial',
        'height',
        'layout_id',
        'live',
        'length',
        'mtplm',
        'name',
        'payload',
        'position',
        'small_print',
        'mro',
        'width',
        'year',
        'video_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'height' => 'decimal:2',
        'length' => 'decimal:2',
        'live' => 'boolean',
        'mtplm' => 'integer',
        'payload' => 'integer',
        'position' => 'integer',
        'mro' => 'integer',
        'width' => 'decimal:2',
        'year' => 'integer',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'live' => true,
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function ($caravan) {
            $caravan->bedSizes()->delete();
        });
    }

    public function berths(): BelongsToMany
    {
        return $this->belongsToMany(Berth::class);
    }

    public function bedSizes(): MorphMany
    {
        return $this->morphMany(BedSize::class, 'vehicle');
    }

    public function caravanRange(): BelongsTo
    {
        return $this->belongsTo(CaravanRange::class);
    }

    public function features(): HasManyThrough
    {
        // Switch the order of the keys as it's a belongs to.
        // The where clause is because range features are polymorphic
        return $this->hasManyThrough(
            RangeFeature::class,
            CaravanRange::class,
            'id',
            'vehicle_range_id',
            'caravan_range_id',
            'id'
        )->where('vehicle_range_type', CaravanRange::class);
    }

    public function rangeSpecificationSmallPrints(): HasManyThrough
    {
        // Switch the order of the keys as it's a belongs to.
        // The where clause is because range features are polymorphic
        return $this->hasManyThrough(
            RangeSpecificationSmallPrint::class,
            MotorhomeRange::class,
            'id',
            'vehicle_range_id',
            'motorhome_range_id',
            'id'
        )->where('vehicle_range_type', CaravanRange::class);
    }

    /**
     * Get all of the sites for the pageable.
     */
    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class)->withPivot([
            'price',
            'recommended_price',
        ]);
    }

    public function stockItem(): HasOne
    {
        return $this->hasOne(CaravanStockItem::class);
    }

    /**
     * Media attached to the caravan range for the caravan that then
     * gets pulled onto the stock item page
     */
    public function stockItemImages(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'caravan_stock_item_images', 'caravan_id', 'media_id');
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function specialOffers(): BelongsToMany
    {
        return $this->belongsToMany(SpecialOffer::class, 'caravan_special_offer', 'caravan_id', 'special_offer_id');
    }

    /**
     * @param mixed $value
     */
    public function setAxlesAttribute($value): void
    {
        if (!in_array($value, static::AXLES)) {
            throw new UnexpectedValueException('Axles value invalid');
        }
        $this->attributes['axles'] = $value;
    }

    public function sluggableSources(): array
    {
        return [
            'caravanRange.name',
            'name',
            'year',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dayFloorplan')->singleFile();
        $this->addMediaCollection('nightFloorplan')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)->height(100);

        $this->addMediaConversion('stockSliderNav')
            ->fit(Manipulations::FIT_CONTAIN, 50, 25)
            ->performOnCollections('dayFloorplan', 'nightFloorplan');

        $this->addMediaConversion('show')
            ->fit(Manipulations::FIT_MAX, 385, 190)
            ->performOnCollections('dayFloorplan', 'nightFloorplan')
            ->withResponsiveImages()
            ->keepOriginalImageFormat()
            ->nonQueued();

        $this->addMediaConversion('stockListing')
            ->fit(Manipulations::FIT_CROP, 350, 260);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPresenterClass(): string
    {
        return CaravanPresenter::class;
    }

    public function shouldPrependRangeName(): bool
    {
        return is_null($this->caravanRange)
            ? false
            : $this->caravanRange->prepend_range_name_to_model_names;
    }
}

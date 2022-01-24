<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use UnexpectedValueException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Interfaces\Vehicle;
use App\Models\Traits\Liveable;
use App\Presenters\MotorhomePresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Motorhome extends Model implements HasMedia, Vehicle, HasPresenter
{
    use InteractsWithMedia;
    use Ransackable;
    use Liveable;

    public const FUEL_DIESEL = 'Diesel';
    public const FUEL_TURBO_DIESEL = 'Turbo Diesel';
    public const FUEL_PETROL = 'Petrol';

    public const FUELS = [
        self::FUEL_DIESEL,
        self::FUEL_TURBO_DIESEL,
        self::FUEL_PETROL,
    ];

    public const TRANSMISSION_MANUAL = 'Manual';
    public const TRANSMISSION_AUTOMATIC = 'Automatic';

    public const TRANSMISSIONS = [
        self::TRANSMISSION_MANUAL,
        self::TRANSMISSION_AUTOMATIC,
    ];

    public const CONVERSION_A_CLASS = 'A Class';
    public const CONVERSION_CAMPERVAN = 'Camper Van';
    public const CONVERSION_COACHBUILT = 'Coachbuilt';

    public const CONVERSIONS = [
        self::CONVERSION_A_CLASS,
        self::CONVERSION_CAMPERVAN,
        self::CONVERSION_COACHBUILT,
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'conversion',
        'chassis_manufacturer',
        'description',
        'engine_size',
        'engine_power',
        'exclusive',
        'fuel',
        'height',
        'high_line_height',
        'height_includes_aerial',
        'layout_id',
        'live',
        'length',
        'mtplm', // Maximum Technically Permissible Laden Mass (MTPLM)
        'name',
        'payload',
        'position',
        'small_print',
        'transmission',
        'mro', // Mass in Running Order (MIRO)
        'video_id',
        'width',
        'year',
        'maximum_trailer_weight',
        'gross_train_weight',
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'height' => 'decimal:2',
        'high_line_height' => 'decimal:2',
        'length' => 'decimal:2',
        'live' => 'boolean',
        'mtplm' => 'integer',
        'payload' => 'integer',
        'position' => 'integer',
        'mro' => 'integer',
        'width' => 'decimal:2',
        'year' => 'integer',
        'maximum_trailer_weight' => 'integer',
        'gross_train_weight' => 'integer',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'live' => true,
    ];

    public function berths(): BelongsToMany
    {
        return $this->belongsToMany(Berth::class);
    }

    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class);
    }

    public function bedSizes(): MorphMany
    {
        return $this->morphMany(BedSize::class, 'vehicle');
    }

    public function motorhomeRange(): BelongsTo
    {
        return $this->belongsTo(MotorhomeRange::class);
    }

    public function features(): HasManyThrough
    {
        // Switch the order of the keys as it's a belongs to.
        // The where clause is because range features are polymorphic
        return $this->hasManyThrough(
            RangeFeature::class,
            MotorhomeRange::class,
            'id',
            'vehicle_range_id',
            'motorhome_range_id',
            'id'
        )->where('vehicle_range_type', MotorhomeRange::class);
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
        )->where('vehicle_range_type', MotorhomeRange::class);
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class)->withPivot([
            'price',
            'recommended_price',
        ]);
    }

    public function stockItem(): HasOne
    {
        return $this->hasOne(MotorhomeStockItem::class);
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function optionalWeights(): HasMany
    {
        return $this->hasMany(OptionalWeight::class);
    }

    public function specialOffers(): BelongsToMany
    {
        return $this->belongsToMany(SpecialOffer::class, 'motorhome_special_offer', 'motorhome_id', 'special_offer_id');
    }

    /**
     * Media attached to the motorhome range for the motorhome that then
     * gets pulled onto the stock item page
     */
    public function stockItemImages(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'motorhome_stock_item_images', 'motorhome_id', 'media_id');
    }

    /**
     * @param mixed $value
     */
    public function setFuelAttribute($value): void
    {
        if (!in_array($value, static::FUELS)) {
            throw new UnexpectedValueException('Fuel value invalid');
        }
        $this->attributes['fuel'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setConversionAttribute($value): void
    {
        if (!in_array($value, static::CONVERSIONS)) {
            throw new UnexpectedValueException('Conversion value invalid');
        }
        $this->attributes['conversion'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setTransmissionAttribute($value): void
    {
        if (!in_array($value, static::TRANSMISSIONS)) {
            throw new UnexpectedValueException('Transmission value invalid');
        }
        $this->attributes['transmission'] = $value;
    }

    public function sluggableSources(): array
    {
        return [
            'motorhomeRange.name',
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
        return MotorhomePresenter::class;
    }

    public function shouldPrependRangeName(): bool
    {
        return is_null($this->motorhomeRange)
            ? false
            : $this->motorhomeRange->prepend_range_name_to_model_names;
    }
}

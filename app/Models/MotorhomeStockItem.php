<?php

namespace App\Models;

use App\Models\Interfaces\StockItem;
use App\Models\Traits\IsStock;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Traits\Pageable;
use App\Presenters\StockItem\MotorhomePresenter;
use App\Services\Search\MotorhomeStockItem\DataProvider;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use UnexpectedValueException;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;

class MotorhomeStockItem extends Model implements StockItem, HasMedia, HasPresenter
{
    use InteractsWithMedia;
    use Pageable;
    use IsStock;
    use Searchable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'attention_grabber',
        "chassis_manufacturer",
        "condition",
        "conversion",
        "delivery_date",
        "demonstrator",
        "description",
        "engine_power",
        "engine_size",
        "fuel",
        "height",
        "live",
        "layout_id",
        "length",
        'managers_special',
        "manufacturer_id",
        "motorhome_range_id",
        "dealer_id",
        "mtplm",
        "mileage",
        "model",
        "payload",
        "number_of_owners",
        "price", // The current price - can be the same as recommended price
        'recommended_price', // Recommended price
        'reduced_price_start_date',
        "registration_date",
        "registration_number",
        "source",
        "transmission",
        "unique_code",
        "mro",
        "width",
        "year",
        'exclusive', // From models created in admin
        'special_offer',
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'delivery_date' => 'date',
        'demonstator' => 'boolean',
        'height' => 'decimal:2',
        'live' => 'boolean',
        'length' => 'decimal:2',
        'mtplm' => 'integer',
        'number_of_owners' => 'integer',
        'payload' => 'integer',
        'price' => 'decimal:2',
        'recommended_price' => 'decimal:2',
        'registration_date' => 'date',
        'mro' => 'integer',
        'width' => 'decimal:2',
        'year' => 'integer',
        'managers_special' => 'boolean',
        'exclusive' => 'boolean',
        'special_offer' => 'boolean',
    ];

    public function berths(): BelongsToMany
    {
        return $this->belongsToMany(Berth::class);
    }

    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class);
    }

    public function features(): Relation
    {
        return $this->hasMany(MotorhomeStockItemFeature::class);
    }

    public function motorhome(): Relation
    {
        return $this->belongsTo(Motorhome::class);
    }

    public function manufacturer(): Relation
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function motorhomeRange(): Relation
    {
        return $this->belongsTo(MotorhomeRange::class);
    }

    public function dealer(): Relation
    {
        return $this->belongsTo(Dealer::class);
    }

    public function layout(): Relation
    {
        return $this->belongsTo(Layout::class);
    }

    public function specialOffers(): BelongsToMany
    {
        return $this->belongsToMany(SpecialOffer::class);
    }

    public function feedStockItemVideo(): MorphOne
    {
        return $this->morphOne(FeedStockItemVideo::class, 'videoable');
    }

    /**
     * @param mixed $value
     */
    public function setConditionAttribute($value): void
    {
        if (!in_array($value, static::CONDITIONS)) {
            throw new UnexpectedValueException('Condition value invalid');
        }
        $this->attributes['condition'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setSourceAttribute($value): void
    {
        if (!in_array($value, static::SOURCES)) {
            throw new UnexpectedValueException('Source value invalid');
        }
        $this->attributes['source'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setConversionAttribute($value): void
    {
        if (!in_array($value, Motorhome::CONVERSIONS)) {
            throw new UnexpectedValueException('Conversion value invalid');
        }
        $this->attributes['conversion'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setTransmissionAttribute($value): void
    {
        if (!in_array($value, Motorhome::TRANSMISSIONS)) {
            throw new UnexpectedValueException('transmission value invalid');
        }
        $this->attributes['transmission'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setFuelAttribute($value): void
    {
        if (!in_array($value, Motorhome::FUELS)) {
            throw new UnexpectedValueException('fuel value invalid');
        }
        $this->attributes['fuel'] = $value;
    }

    /**
     * Called when pages are created
     */
    public function sluggableSources(): array
    {
        return [
            'manufacturer.name',
            'model',
            'unique_code',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('layout')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('responsiveStockSlider')
            ->fit(Manipulations::FIT_CONTAIN, 600, 450)
            ->withResponsiveImages();

        $this->addMediaConversion('thumbStockSlider')
            ->fit(Manipulations::FIT_CONTAIN, 120, 120);

        $this->addMediaConversion('stockListing')
            ->fit(Manipulations::FIT_CROP, 350, 260);
    }

    public function shouldPrependRangeName(): bool
    {
        return (is_null($this->motorhome) || is_null($this->motorhome->motorhomeRange))
            ? false
            : $this->motorhome->motorhomeRange->prepend_range_name_to_model_names;
    }

    public function rangeName(): string
    {
        return (is_null($this->motorhome) || is_null($this->motorhome->motorhomeRange))
            ? ''
            : $this->motorhome->motorhomeRange->name;
    }

    public function stockType(): string
    {
        return self::TYPE_MOTORHOME;
    }

    public function manufacturerName(): string
    {
        if ($manufacturer = $this->manufacturer) {
            return $manufacturer->name;
        }
        return "";
    }

    public function layoutName(): string
    {
        if ($layout = $this->layout) {
            return $layout->name;
        }
        return "";
    }

    public function getPresenterClass(): string
    {
        return MotorhomePresenter::class;
    }

    public function modelImages(): Collection
    {
        if ($this->hasFeedSource()) {
            return $this->getMedia('images');
        }

        if (is_null($this->motorhome)) {
            return collect();
        }

        return $this->motorhome
            ->stockItemImages()
            ->whereIn('collection_name', [MotorhomeRange::GALLERY_EXTERIOR, MotorhomeRange::GALLERY_INTERIOR])
            ->orderBy('collection_name', 'asc')
            ->get();
    }

    public function floorplanImages(): Collection
    {
        if ($this->hasFeedSource()) {
            return $this->getMedia('layout');
        }

        if (is_null($this->motorhome)) {
            return collect();
        }

        $dayFloorplanCollection = $this->motorhome->getMedia('dayFloorplan');
        $nightFloorplanCollection = $this->motorhome->getMedia('nightFloorplan');

        return $dayFloorplanCollection->merge($nightFloorplanCollection);
    }

    public function searchableAs(): string
    {
        return config('scout.indexes.motorhome-stock-items.name');
    }

    public function shouldBeSearchable(): bool
    {
        return $this->live;
    }

    public function searchIndexShouldBeUpdated(): bool
    {
        $dirtyData = $this->getDirty();
        // The Importer always sets live to false so we need to ignore this.
        // GetDirty always thinks that the booleans have changed
        Arr::forget($dirtyData, [
            'live', // Importer sets live to false on beginning import
            'updated_at', // If not ignored will make the page for the stock item index to algolia
            'demonstrator' // getDirty does not seem to like booleans
        ]);

        return count($dirtyData) > 0;
    }

    public function toSearchableArray(): array
    {
        return DataProvider::getSearchIndexData($this);
    }
}

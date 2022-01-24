<?php

namespace App\Models;

use App\Models\Interfaces\StockItem;
use App\Models\Traits\IsStock;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Traits\Pageable;
use App\Presenters\StockItem\CaravanPresenter;
use App\Services\Search\CaravanStockItem\DataProvider;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use UnexpectedValueException;

class CaravanStockItem extends Model implements StockItem, HasMedia, HasPresenter
{
    use InteractsWithMedia;
    use Pageable;
    use IsStock;
    use Searchable;

    /**
     * @var array
     */
    protected $fillable = [
        'attention_grabber',
        'axles',
        'caravan_id',
        'condition',
        'delivery_date',
        'demonstrator',
        'description',
        'height',
        'live',
        'layout_id',
        'length',
        'managers_special',
        'manufacturer_id',
        'caravan_range_id',
        'dealer_id',
        'mtplm', // Maximum Technically Permissible Laden Mass (MTPLM)
        'model',
        'number_of_owners',
        'payload',
        'price', // The current price - can be the same as recommended price
        'recommended_price', // Recommended price
        'reduced_price_start_date',
        'registration_date',
        'source',
        'unique_code',
        'mro', // Mass in Running Order (MIRO)
        'width',
        'year',
        'exclusive', // From models created in admin
        'special_offer',
    ];

    /**
     * @var array
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
        'exclusive' => 'boolean',
        'managers_special' => 'boolean',
        'special_offer' => 'boolean',
    ];

    public function berths(): BelongsToMany
    {
        return $this->belongsToMany(Berth::class);
    }

    public function caravan(): Relation
    {
        return $this->belongsTo(Caravan::class);
    }

    public function features(): Relation
    {
        return $this->hasMany(CaravanStockItemFeature::class);
    }

    public function manufacturer(): Relation
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function caravanRange(): Relation
    {
        return $this->belongsTo(CaravanRange::class);
    }

    public function dealer(): Relation
    {
        return $this->belongsTo(Dealer::class);
    }

    public function specialOffers(): BelongsToMany
    {
        return $this->belongsToMany(SpecialOffer::class);
    }

    public function layout(): Relation
    {
        return $this->belongsTo(Layout::class);
    }

    public function feedStockItemVideo(): MorphOne
    {
        return $this->morphOne(FeedStockItemVideo::class, 'videoable');
    }

    /**
     * @param mixed $value
     */
    public function setAxlesAttribute($value): void
    {
        if (!in_array($value, Caravan::AXLES)) {
            throw new UnexpectedValueException('Axles value invalid');
        }
        $this->attributes['axles'] = $value;
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
        return (is_null($this->caravan) || is_null($this->caravan->caravanRange))
            ? false
            : $this->caravan->caravanRange->prepend_range_name_to_model_names;
    }

    public function rangeName(): string
    {
        return (is_null($this->caravan) || is_null($this->caravan->caravanRange))
            ? ''
            : $this->caravan->caravanRange->name;
    }

    public function stockType(): string
    {
        return self::TYPE_CARAVAN;
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
        return CaravanPresenter::class;
    }

    public function modelImages(): Collection
    {
        if ($this->hasFeedSource()) {
            return $this->getMedia('images');
        }

        if (is_null($this->caravan)) {
            return collect();
        }

        return $this->caravan
            ->stockItemImages()
            ->whereIn('collection_name', [CaravanRange::GALLERY_EXTERIOR, CaravanRange::GALLERY_INTERIOR])
            ->orderBy('collection_name', 'asc')
            ->get();
    }

    public function floorplanImages(): Collection
    {
        if ($this->hasFeedSource()) {
            return $this->getMedia('layout');
        }

        if (is_null($this->caravan)) {
            return collect();
        }

        $dayFloorplanCollection = $this->caravan->getMedia('dayFloorplan');
        $nightFloorplanCollection = $this->caravan->getMedia('nightFloorplan');

        return $dayFloorplanCollection->merge($nightFloorplanCollection);
    }

    public function searchableAs(): string
    {
        return config('scout.indexes.caravan-stock-items.name');
    }

    public function shouldBeSearchable(): bool
    {
        return $this->live;
    }

    public function searchIndexShouldBeUpdated(): bool
    {
        $dirtyData = $this->getDirty();
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

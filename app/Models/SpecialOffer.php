<?php

namespace App\Models;

use App\Models\Interfaces\HasThemedProperties;
use Illuminate\Database\Eloquent\Model;
use UnexpectedValueException;
use App\Models\Traits\Expirable;
use App\Models\Traits\Featureable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Pageable;
use App\Models\Traits\Publishable;
use App\Models\Traits\ThemedProperties;
use App\Presenters\SpecialOfferPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use InvalidArgumentException;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SpecialOffer extends Model implements HasMedia, HasPresenter, HasThemedProperties
{
    use Expirable;
    use Featureable;
    use InteractsWithMedia;
    use Liveable;
    use Pageable;
    use Publishable;
    use Ransackable;
    use ThemedProperties;

    public const TYPE_BOTH = 'Both';
    public const TYPE_CARAVAN = 'Caravan';
    public const TYPE_MOTORHOME = 'Motorhome';

    public const TYPES = [
        self::TYPE_BOTH,
        self::TYPE_CARAVAN,
        self::TYPE_MOTORHOME,
    ];

    public const CARAVAN_TYPES = [
        self::TYPE_BOTH,
        self::TYPE_CARAVAN,
    ];

    public const MOTORHOME_TYPES = [
        self::TYPE_BOTH,
        self::TYPE_MOTORHOME,
    ];

    /**
     * The offers that can be created in tha admin
     *
     * See the isStock trait for other "offer types" on search not done via special offers
     */
    public const OFFER_TYPES = [
        'Free Accessories',
        'Seasonal',
        'Special Offers',
    ];

    public const STOCK_BAR_COLOURS = HasThemedProperties::COLOURS;
    public const STOCK_BAR_TEXT_COLOURS = HasThemedProperties::COLOURS;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'content',
        'expired_at',
        'icon',
        'offer_type',
        'name',
        'published_at',
        'type',
        'live',
        'link_used_caravan_stock',
        'link_used_motorhome_stock',
        'link_managers_special_stock',
        'link_on_sale_stock',
        'link_feed_special_offers_caravans',
        'link_feed_special_offers_motorhomes',
        'position',
        'stock_bar_colour',
        'stock_bar_text_colour',
        'url',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'live' => 'boolean',
        'link_used_caravan_stock' => 'boolean',
        'link_used_motorhome_stock' => 'boolean',
        'link_managers_special_stock' => 'boolean',
        'link_on_sale_stock' => 'boolean',
        'link_feed_special_offers_caravans' => 'boolean',
        'link_feed_special_offers_motorhomes' => 'boolean',
    ];

    public function caravans(): BelongsToMany
    {
        return $this->belongsToMany(Caravan::class);
    }

    public function caravanStockItems(): BelongsToMany
    {
        return $this->belongsToMany(CaravanStockItem::class);
    }

    public function motorhomes(): BelongsToMany
    {
        return $this->belongsToMany(Motorhome::class);
    }

    public function motorhomeStockItems(): BelongsToMany
    {
        return $this->belongsToMany(MotorhomeStockItem::class);
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class);
    }

    public function scopeOrderedByPosition(Builder $query): Builder
    {
        return $query->orderBy('position', 'asc');
    }

    public function scopeForCaravans(Builder $query): Builder
    {
        return $query->whereIn('type', self::CARAVAN_TYPES);
    }

    public function scopeForMotorhomes(Builder $query): Builder
    {
        return $query->whereIn('type', self::MOTORHOME_TYPES);
    }

    public function scopeForSite(Builder $query, Site $site): Builder
    {
        return $query->join('site_special_offer', function ($join) use ($site) {
            $join->on('special_offers.id', '=', 'site_special_offer.special_offer_id')
                ->where('site_special_offer.site_id', $site->id);
        });
    }

    public function scopeDisplayable(Builder $query): Builder
    {
        return $query->live()->published()->notExpired();
    }

    public function setStockBarColourAttribute(string $colour): void
    {
        if (!in_array($colour, array_keys(self::STOCK_BAR_COLOURS))) {
            throw new InvalidArgumentException('Invalid stock bar colour');
        }

        $this->attributes['stock_bar_colour'] = $colour;
    }

    public function setStockBarTextColourAttribute(string $colour): void
    {
        if (!in_array($colour, array_keys(self::STOCK_BAR_TEXT_COLOURS))) {
            throw new InvalidArgumentException('Invalid stock bar text colour');
        }

        $this->attributes['stock_bar_text_colour'] = $colour;
    }

    /**
     * @param mixed $value
     */
    public function setTypeAttribute($value): void
    {
        if (!in_array($value, static::TYPES)) {
            throw new UnexpectedValueException('Type value invalid');
        }
        $this->attributes['type'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setOfferTypeAttribute($value): void
    {
        if (!in_array($value, static::OFFER_TYPES)) {
            throw new UnexpectedValueException('Offer type value invalid');
        }
        $this->attributes['offer_type'] = $value;
    }

    public function sluggableSources(): array
    {
        return [
            'name',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('squareImage')->singleFile();
        $this->addMediaCollection('landscapeImage')->singleFile();
        $this->addMediaCollection('document')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)
            ->height(80);

        $this->addMediaConversion('responsive')
            ->withResponsiveImages()
            ->keepOriginalImageFormat();
    }

    public function getImageUrlBySize(string $size): ?string
    {
        return $this->getFirstMediaUrl($size);
    }

    public function getPresenterClass(): string
    {
        return SpecialOfferPresenter::class;
    }

    public static function iconDirectoryPath(): string
    {
        return public_path('images/special-offer-icons');
    }

    public static function iconDirectoryUrl(): string
    {
        return url('images/special-offer-icons');
    }
}

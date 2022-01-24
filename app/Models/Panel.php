<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use UnexpectedValueException;
use App\Models\Traits\Expirable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Publishable;
use App\Presenters\PanelType\BasePanelPresenter;
use App\Presenters\PanelType\BrochurePresenter;
use App\Presenters\PanelType\EventPresenter;
use App\Presenters\PanelType\FormPresenter;
use App\Presenters\PanelType\FeaturedImagePanelPresenter;
use App\Presenters\PanelType\HtmlPanelPresenter;
use App\Presenters\PanelType\ImagePanelPresenter;
use App\Presenters\PanelType\ManufacturerSliderPresenter;
use App\Presenters\PanelType\QuotePresenter;
use App\Presenters\PanelType\ReadMorePresenter;
use App\Presenters\PanelType\ReviewPresenter;
use App\Presenters\PanelType\SearchByBerthPresenter;
use App\Presenters\PanelType\SpecialOfferSliderPresenter;
use App\Presenters\PanelType\StockItemCategoryTabsPresenter;
use App\Presenters\PanelType\VideoPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Panel extends Model implements HasMedia, HasPresenter
{
    use InteractsWithMedia;
    use Publishable;
    use Expirable;
    use Liveable;

    /**
     * @var array $attributes
     */
    protected $attributes = [
        'live' => true,
        'position' => 0,
        'type' => 'standard',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'area_id',
        'content',
        'html_content',
        'expired_at',
        'featureable_id',
        'featureable_type',
        'featured_image_content',
        'featured_image_alt_text',
        'image_alt_text',
        'page_id',
        'external_url',
        'heading',
        'heading_type',
        'live',
        'name',
        'overlay_position',
        'position',
        'published_at',
        'read_more_content',
        'text_alignment',
        'type',
        'vertical_positioning',
        'vehicle_type',
    ];

    /**
     * These types determine the partial used in resources/views/site/panels
     */
    public const TYPE_FEATURED_IMAGE = 'featured-image';
    public const TYPE_FORM = 'form';
    public const TYPE_IMAGE = 'image';
    public const TYPE_MANUFACTURER_SLIDER = 'manufacturer-slider';
    public const TYPE_QUOTE = 'quote';
    public const TYPE_READ_MORE = 'read-more';
    public const TYPE_SEARCH_BY_BERTH = 'search-by-berth';
    public const TYPE_SPECIAL_OFFERS = 'special-offers';
    public const TYPE_STANDARD = 'standard';
    public const TYPE_STOCK_ITEM_CATEGORY_TABS = 'stock-item-category-tabs';
    public const TYPE_VIDEO = 'video';
    public const TYPE_HTML = 'html';
    public const TYPE_REVIEW = 'review';
    public const TYPE_EVENT = 'event';
    public const TYPE_BROCHURE = 'brochure';

    public const TYPES = [
        self::TYPE_FEATURED_IMAGE => 'Featured Image',
        self::TYPE_IMAGE => 'Image',
        self::TYPE_MANUFACTURER_SLIDER => 'Manufacturer Slider',
        self::TYPE_QUOTE => 'Quote',
        self::TYPE_READ_MORE => 'Read More',
        self::TYPE_SEARCH_BY_BERTH => 'Search By Berth',
        self::TYPE_STANDARD => 'Standard',
        self::TYPE_STOCK_ITEM_CATEGORY_TABS => 'Stock Item Category Tabs',
        self::TYPE_SPECIAL_OFFERS => 'Special Offers',
        self::TYPE_FORM => 'Form',
        self::TYPE_VIDEO => 'Video',
        self::TYPE_HTML => 'HTML',
        self::TYPE_REVIEW => 'Review',
        self::TYPE_EVENT => 'Event',
        self::TYPE_BROCHURE => 'Brochure',
    ];

    public const TEXT_ALIGNMENT_LEFT = 'text-left';
    public const TEXT_ALIGNMENT_CENTRE = 'text-center';
    public const TEXT_ALIGNMENT_RIGHT = 'text-right';
    public const TEXT_ALIGNMENT_JUSTIFY = 'text-justify';

    public const TEXT_ALIGNMENTS = [
        self::TEXT_ALIGNMENT_LEFT => 'Left',
        self::TEXT_ALIGNMENT_CENTRE => 'Centre',
        self::TEXT_ALIGNMENT_RIGHT => 'Right',
        self::TEXT_ALIGNMENT_JUSTIFY => 'Justify',
    ];

    public const POSITION_TOP = 'top';
    public const POSITION_MIDDLE = 'middle';
    public const POSITION_BOTTOM = 'bottom';
    public const POSITION_STRETCH = 'stretch';

    public const VERTICAL_POSITIONS = [
        self::POSITION_STRETCH => 'Full height',
        self::POSITION_TOP => 'Top',
        self::POSITION_MIDDLE => 'Middle',
        self::POSITION_BOTTOM => 'Bottom',
    ];

    public const OVERLAY_LEFT = 'left';
    public const OVERLAY_RIGHT = 'right';

    public const OVERLAY_POSITIONS = [
        self::OVERLAY_LEFT => 'Left',
        self::OVERLAY_RIGHT => 'Right',
    ];

    public const VEHICLE_TYPE_BOTH = 'both';
    public const VEHICLE_TYPE_CARAVAN = 'caravan';
    public const VEHICLE_TYPE_MOTORHOME = 'motorhome';

    public const VEHICLE_TYPES = [
        self::VEHICLE_TYPE_BOTH => 'Both',
        self::VEHICLE_TYPE_CARAVAN => 'Caravan',
        self::VEHICLE_TYPE_MOTORHOME => 'Motorhome',
    ];

    public const HEADING_TYPES = [
        'h1',
        'h2',
        'h3',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function featureable(): MorphTo
    {
        return $this->morphTo();
    }

    public function specialOffers(): BelongsToMany
    {
        return $this->belongsToMany(SpecialOffer::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @param mixed $value
     */
    public function setHeadingTypeAttribute($value): void
    {
        if (!in_array($value, array_values(static::HEADING_TYPES))) {
            throw new UnexpectedValueException('heading type value invalid');
        }
        $this->attributes['heading_type'] = $value;
    }


    /**
     * @param mixed $value
     */
    public function setTypeAttribute($value): void
    {
        if (!in_array($value, array_keys(static::TYPES))) {
            throw new UnexpectedValueException('Type value invalid');
        }
        $this->attributes['type'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setVerticalPositioningAttribute($value): void
    {
        if (!in_array($value, array_keys(static::VERTICAL_POSITIONS))) {
            throw new UnexpectedValueException('Vertical positioning value invalid');
        }
        $this->attributes['vertical_positioning'] = $value;
    }

    public function getPresenterClass(): string
    {
        switch ($this->type) {
            case self::TYPE_SEARCH_BY_BERTH:
                return SearchByBerthPresenter::class;
            case self::TYPE_SPECIAL_OFFERS:
                return SpecialOfferSliderPresenter::class;
            case self::TYPE_STOCK_ITEM_CATEGORY_TABS:
                return StockItemCategoryTabsPresenter::class;
            case self::TYPE_MANUFACTURER_SLIDER:
                return ManufacturerSliderPresenter::class;
            case self::TYPE_FORM:
                return FormPresenter::class;
            case self::TYPE_IMAGE:
                return ImagePanelPresenter::class;
            case self::TYPE_READ_MORE:
                return ReadMorePresenter::class;
            case self::TYPE_QUOTE:
                return QuotePresenter::class;
            case self::TYPE_FEATURED_IMAGE:
                return FeaturedImagePanelPresenter::class;
            case self::TYPE_VIDEO:
                return VideoPresenter::class;
            case self::TYPE_HTML:
                return HtmlPanelPresenter::class;
            case self::TYPE_BROCHURE:
                return BrochurePresenter::class;
            case self::TYPE_REVIEW:
                return ReviewPresenter::class;
            case self::TYPE_EVENT:
                return EventPresenter::class;
            default:
                return BasePanelPresenter::class;
        }
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('featuredImage')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)
            ->height(80);

        $this->addMediaConversion('responsive')
            ->withResponsiveImages();
    }

    public function getAltTextForImagePanelTypes(): string
    {
        switch ($this->type) {
            case self::TYPE_IMAGE:
                return $this->image_alt_text;
            case self::TYPE_FEATURED_IMAGE:
                return $this->featured_image_alt_text;
            default:
                return '';
        }
    }

    public function hasTopPositioning(): bool
    {
        return $this->vertical_positioning === self::POSITION_TOP;
    }

    public function hasMiddlePositioning(): bool
    {
        return $this->vertical_positioning === self::POSITION_MIDDLE;
    }

    public function hasBottomPositioning(): bool
    {
        return $this->vertical_positioning === self::POSITION_BOTTOM;
    }

    public function humanisedType(): string
    {
        if (empty($this->type)) {
            return "None";
        }
        return self::TYPES[$this->type];
    }
}

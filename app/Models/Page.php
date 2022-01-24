<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Expirable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Publishable;
use App\Presenters\Page\BasePagePresenter;
use App\Presenters\Page\CaravanRangePresenter;
use App\Presenters\Page\ManufacturerPresenter;
use App\Presenters\Page\MotorhomeRangePresenter;
use App\Presenters\Page\SpecialOfferPresenter;
use App\Services\Search\Page\DataProviderFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\HasPresenter;
use Jdexx\EloquentRansack\Ransackable;
use UnexpectedValueException;
use Laravel\Scout\Searchable;

class Page extends Model implements HasPresenter
{
    use Sluggable;
    use Expirable;
    use Liveable;
    use Publishable;
    use Ransackable;
    use Searchable;

    public const TEMPLATE_ARTICLES_LISTING = "articles-listing";
    public const TEMPLATE_ARTICLE_SHOW = "article";
    public const TEMPLATE_BROCHURES_LISTING = "brochures-listing";
    public const TEMPLATE_BROCHURES_BY_POST = "brochures-by-post";
    public const TEMPLATE_CARAVAN_RANGE = "caravan-range";
    public const TEMPLATE_CARAVAN_COMPARISON = "caravan-comparison";
    public const TEMPLATE_CARAVAN_SEARCH = "caravan-search";
    public const TEMPLATE_CARAVAN_STOCK_ITEM = "caravan-stock-item";
    public const TEMPLATE_DEALERS_LISTING = "dealers-listing";
    public const TEMPLATE_DEALER_SHOW = "dealer";
    public const TEMPLATE_EVENTS_LISTING = "events-listing";
    public const TEMPLATE_EVENT_SHOW = "event";
    public const TEMPLATE_HOMEPAGE = "homepage";
    public const TEMPLATE_MANUFACTURER_CARAVANS = "manufacturer-caravans";
    public const TEMPLATE_MANUFACTURER_MOTORHOMES = "manufacturer-motorhomes";
    public const TEMPLATE_NEW_CARAVANS = "new-caravans";
    public const TEMPLATE_NEW_MOTORHOMES = "new-motorhomes";
    public const TEMPLATE_MOTORHOME_RANGE = "motorhome-range";
    public const TEMPLATE_MOTORHOME_COMPARISON = "motorhome-comparison";
    public const TEMPLATE_MOTORHOME_SEARCH = "motorhome-search";
    public const TEMPLATE_MOTORHOME_STOCK_ITEM = "motorhome-stock-item";
    public const TEMPLATE_TESTIMONIALS_LISTING = "testimonials-listing";
    public const TEMPLATE_TELL_US_YOUR_STORY_LISTING = "tell-us-your-story-listing";
    public const TEMPLATE_SPECIAL_OFFERS_LISTING = "special-offers-listing";
    public const TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW = "special-offer-caravan";
    public const TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW = "special-offer-motorhome";
    public const TEMPLATE_STANDARD = "standard";
    public const TEMPLATE_NEWS_AND_INFO_LANDER = 'news-and-info-lander';
    public const TEMPLATE_USEFUL_LINK_LISTING = "useful-links-listing";
    public const TEMPLATE_VIDEOS_LISTING = "videos-listing";
    public const TEMPLATE_VIDEO_SHOW = "video";
    public const TEMPLATE_REVIEWS_LISTING = "reviews-listing";
    public const TEMPLATE_VACANCIES_LISTING = "vacancies-listing";
    public const TEMPLATE_VACANCY_SHOW = "vacancy-show";
    public const TEMPLATE_SEARCH = 'search';

    /**
     * All possible templates for a page
     */
    public const TEMPLATES = [
        self::TEMPLATE_STANDARD,
        self::TEMPLATE_ARTICLES_LISTING,
        self::TEMPLATE_ARTICLE_SHOW,
        self::TEMPLATE_BROCHURES_LISTING,
        self::TEMPLATE_BROCHURES_BY_POST,
        self::TEMPLATE_EVENTS_LISTING,
        self::TEMPLATE_EVENT_SHOW,
        self::TEMPLATE_VIDEOS_LISTING,
        self::TEMPLATE_VIDEO_SHOW,
        self::TEMPLATE_NEW_CARAVANS,
        self::TEMPLATE_NEW_MOTORHOMES,
        self::TEMPLATE_CARAVAN_SEARCH,
        self::TEMPLATE_CARAVAN_STOCK_ITEM,
        self::TEMPLATE_MOTORHOME_SEARCH,
        self::TEMPLATE_MOTORHOME_STOCK_ITEM,
        self::TEMPLATE_MANUFACTURER_CARAVANS,
        self::TEMPLATE_MANUFACTURER_MOTORHOMES,
        self::TEMPLATE_MOTORHOME_RANGE,
        self::TEMPLATE_MOTORHOME_COMPARISON,
        self::TEMPLATE_CARAVAN_RANGE,
        self::TEMPLATE_CARAVAN_COMPARISON,
        self::TEMPLATE_DEALERS_LISTING,
        self::TEMPLATE_DEALER_SHOW,
        self::TEMPLATE_TESTIMONIALS_LISTING,
        self::TEMPLATE_SPECIAL_OFFERS_LISTING,
        self::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
        self::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
        self::TEMPLATE_HOMEPAGE,
        self::TEMPLATE_REVIEWS_LISTING,
        self::TEMPLATE_USEFUL_LINK_LISTING,
        self::TEMPLATE_VACANCIES_LISTING,
        self::TEMPLATE_VACANCY_SHOW,
        self::TEMPLATE_NEWS_AND_INFO_LANDER,
        self::TEMPLATE_SEARCH,
        self::TEMPLATE_TELL_US_YOUR_STORY_LISTING,
    ];

    /**
     * Standard Templates are those created by an administrator.
     * The other templates are created by "pageables" - models that then create a page when they are created
     */
    public const STANDARD_TEMPLATES = [
        self::TEMPLATE_STANDARD => "Standard",
        self::TEMPLATE_ARTICLES_LISTING => "Articles Listing",
        self::TEMPLATE_BROCHURES_LISTING => "Brochures Listing",
        self::TEMPLATE_BROCHURES_BY_POST => "Brochures By Post",
        self::TEMPLATE_CARAVAN_COMPARISON => "Caravan Comparison",
        self::TEMPLATE_CARAVAN_SEARCH => "Caravan Search",
        self::TEMPLATE_DEALERS_LISTING => "Dealers Listing",
        self::TEMPLATE_EVENTS_LISTING => "Events Listing",
        self::TEMPLATE_HOMEPAGE => "Home",
        self::TEMPLATE_MOTORHOME_COMPARISON => "Motorhome Comparison",
        self::TEMPLATE_MOTORHOME_SEARCH => "Motorhome Search",
        self::TEMPLATE_NEW_CARAVANS => "New Caravans",
        self::TEMPLATE_NEW_MOTORHOMES => "New Motorhomes",
        self::TEMPLATE_SPECIAL_OFFERS_LISTING => "Special Offers Listing",
        self::TEMPLATE_TESTIMONIALS_LISTING => "Testimonials Listing",
        self::TEMPLATE_VACANCIES_LISTING => "Vacancies Listing",
        self::TEMPLATE_VIDEOS_LISTING => "Videos Listing",
        self::TEMPLATE_REVIEWS_LISTING => "Reviews Listing",
        self::TEMPLATE_USEFUL_LINK_LISTING => "Useful Link Listing",
        self::TEMPLATE_NEWS_AND_INFO_LANDER => "New and Info Landing",
        self::TEMPLATE_SEARCH => "Search",
        self::TEMPLATE_TELL_US_YOUR_STORY_LISTING => "Tell Us Your Story Listing",
    ];

    public static function allTemplates(): Collection
    {
        return collect(self::TEMPLATES)->sort()->mapWithKeys(function ($template) {
            $key = $template;
            if (array_key_exists($template, self::STANDARD_TEMPLATES)) {
                $value = self::STANDARD_TEMPLATES[$template];
            } else {
                $template = str_replace('-', ' ', $template);
                if (is_array($template)) {
                    $template = implode(' ', $template);
                }
                $value = Str::title($template);
            }

            return [$key => $value];
        });
    }

    public const VARIETY_ABOUT_US = 'About Us';
    public const VARIETY_ACCESSORIES = 'Accessories';
    public const VARIETY_AUTOMARQ = 'Automarq';
    public const VARIETY_CARAMARQ = 'Caramarq';
    public const VARIETY_SERVICES = 'Services';
    public const VARIETY_STANDARD = 'Standard';
    public const VARIETY_NEWSLETTER_SIGN_UP = 'Newsletter Sign Up';
    public const VARIETY_CONTACT_US = 'Contact Us';
    public const VARIETY_PREFERRED_DEALER = 'Preferred Dealer';
    public const VARIETY_SEND_US_YOUR_STORY = 'Send Us Your Story';
    public const VARIETY_COOKIE_POLICY = 'Cookie Policy';
    public const VARIETY_MANUFACTURERS_WARRANTY = 'Manufacturer\'s Warranty';

    public const VARIETIES = [
        self::VARIETY_STANDARD,
        self::VARIETY_ABOUT_US,
        self::VARIETY_ACCESSORIES,
        self::VARIETY_AUTOMARQ,
        self::VARIETY_CARAMARQ,
        self::VARIETY_CONTACT_US,
        self::VARIETY_NEWSLETTER_SIGN_UP,
        self::VARIETY_SERVICES,
        self::VARIETY_PREFERRED_DEALER,
        self::VARIETY_SEND_US_YOUR_STORY,
        self::VARIETY_COOKIE_POLICY,
        self::VARIETY_MANUFACTURERS_WARRANTY,
    ];

    /**
     * @var array $attributes
     */
    protected $attributes = [
        'variety' => self::VARIETY_STANDARD,
        'template' => self::TEMPLATE_STANDARD,
        'live' => true,
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'expired_at',
        'live',
        'name',
        'meta_title',
        'meta_description',
        'parent_id',
        'published_at',
        'site_id',
        'template',
        'variety',
        'video_banner_id',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function imageBanners(): BelongsToMany
    {
        return $this->belongsToMany(ImageBanner::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function videoBanner(): BelongsTo
    {
        return $this->belongsTo(VideoBanner::class);
    }

    /**
     * Every web page on the site is a page.  Some models can be "pageable" - i.e. they have pages that belong to them
     */
    public function pageable(): Relation
    {
        return $this->morphTo();
    }

    public function scopeForSite(Builder $query, Site $site): Builder
    {
        return $query->where('site_id', $site->id);
    }

    public function scopeTemplate(Builder $query, string $template): Builder
    {
        return $query->where('template', $template);
    }

    public function scopeVariety(Builder $query, string $variety): Builder
    {
        return $query->where('variety', $variety);
    }

    public function scopeDisplayable(Builder $query): Builder
    {
        return $query->live()->published()->notExpired();
    }

    /**
     * @param mixed $value
     */
    public function setVarietyAttribute($value): void
    {
        if (!in_array($value, self::VARIETIES)) {
            throw new UnexpectedValueException('Invalid variety');
        }
        $this->attributes['variety'] = $value;
    }

    public function hasParent(): bool
    {
        return null !== $this->parent;
    }

    public function sluggable(): array
    {
        if (is_null($this->pageable)) {
            return ['slug' => ['source' => 'name']];
        }
        $sources = $this->pageable->sluggableSources();
        $sources = array_map(function ($source) {
            return "pageable." . $source;
        }, $sources);
        $sources[] = 'slug_additional';

        return ['slug' => ['source' => $sources]];
    }

    /**
     * Used to add additional elements to slug generation.
     *
     * Currently only used on special offers to generate unique slugs for the caravan
     * and motorhome page variants
     */
    public function getSlugAdditionalAttribute(): ?string
    {
        switch ($this->template) {
            case self::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW:
                return 'caravan';
            case self::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW:
                return 'motorhome';
            default:
                return null;
        }
    }

    public function scopeWithUniqueSlugConstraints(
        Builder $query,
        Model $model,
        string $attribute,
        array $config,
        string $slug
    ): Builder {
        switch ($this->pageable_type) {
            case CaravanRange::class:
                return $query->join('caravan_ranges', 'caravan_ranges.id', '=', "{$model->getTable()}.pageable_id")
                    ->where('caravan_ranges.manufacturer_id', $model->pageable->manufacturer_id)
                    ->where('site_id', '=', $model->site_id)
                    ->where('parent_id', '=', $model->parent_id)
                    ->distinct();
            case Caravan::class:
                return $query->join('caravans', 'caravans.id', '=', "{$model->getTable()}.pageable_id")
                    ->where('caravans.caravan_range_id', $model->pageable->caravan_range_id)
                    ->where('site_id', '=', $model->site_id)
                    ->where('parent_id', '=', $model->parent_id)
                    ->distinct();
            case MotorhomeRange::class:
                return $query->join('motorhome_ranges', 'motorhome_ranges.id', '=', "{$model->getTable()}.pageable_id")
                    ->where('motorhome_ranges.manufacturer_id', $model->pageable->manufacturer_id)
                    ->where('site_id', '=', $model->site_id)
                    ->where('parent_id', '=', $model->parent_id)
                    ->distinct();
            case Motorhome::class:
                return $query->join('motorhomes', 'motorhomes.id', '=', "{$model->getTable()}.pageable_id")
                    ->where('motorhomes.motorhome_range_id', $model->pageable->motorhome_range_id)
                    ->where('site_id', '=', $model->site_id)
                    ->where('parent_id', '=', $model->parent_id)
                    ->distinct();
            default:
                return $query->where('site_id', '=', $model->site_id)
                    ->where('parent_id', '=', $model->parent_id);
        }
    }

    public function hasPageable(): bool
    {
        return null !== $this->pageable;
    }

    public function templateName(): string
    {
        if (in_array($this->template, static::STANDARD_TEMPLATES)) {
            return Arr::get(static::STANDARD_TEMPLATES, $this->template);
        }

        $template = (string) $this->template;
        $name = str_replace("-", " ", $template);
        return ucwords($name);
    }

    public function availableHolders(): array
    {
        switch ($this->template) {
            case self::TEMPLATE_ARTICLES_LISTING:
            case self::TEMPLATE_ARTICLE_SHOW:
            case self::TEMPLATE_BROCHURES_LISTING:
            case self::TEMPLATE_BROCHURES_BY_POST:
            case self::TEMPLATE_EVENTS_LISTING:
            case self::TEMPLATE_MANUFACTURER_CARAVANS:
            case self::TEMPLATE_MANUFACTURER_MOTORHOMES:
            case self::TEMPLATE_NEW_CARAVANS:
            case self::TEMPLATE_NEW_MOTORHOMES:
            case self::TEMPLATE_REVIEWS_LISTING:
            case self::TEMPLATE_TESTIMONIALS_LISTING:
            case self::TEMPLATE_VIDEOS_LISTING:
            case self::TEMPLATE_VIDEO_SHOW:
            case self::TEMPLATE_NEWS_AND_INFO_LANDER:
            case self::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW:
            case self::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW:
                return ['Primary'];
            case self::TEMPLATE_EVENT_SHOW:
            case self::TEMPLATE_VACANCIES_LISTING:
                return ['Primary', 'Secondary'];
            case self::TEMPLATE_CARAVAN_RANGE:
            case self::TEMPLATE_MOTORHOME_RANGE:
                return ['Models', 'Technical', 'Buy', 'Offers'];
            default:
                return ['Primary', 'Tabbed', 'Secondary'];
        }
    }

    public function hasTemplate(string $template): bool
    {
        return $this->template === $template;
    }

    public function hasVariety(string $variety): bool
    {
        return $this->variety === $variety;
    }

    public function getPresenterClass(): string
    {
        switch ($this->template) {
            case self::TEMPLATE_MANUFACTURER_CARAVANS:
            case self::TEMPLATE_MANUFACTURER_MOTORHOMES:
                return ManufacturerPresenter::class;
            case self::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW:
            case self::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW:
                return SpecialOfferPresenter::class;
            case self::TEMPLATE_MOTORHOME_RANGE:
                return MotorhomeRangePresenter::class;
            case self::TEMPLATE_CARAVAN_RANGE:
                return CaravanRangePresenter::class;
            default:
                return BasePagePresenter::class;
        }
    }

    public function searchableAs(): string
    {
        return config('scout.indexes.site-search.name');
    }

    public function shouldBeSearchable(): bool
    {
        return $this->isLive() && $this->isPublished() && !$this->hasExpired();
    }

    public function toSearchableArray(): array
    {
        return DataProviderFactory::getDataProvider($this)->generateSiteSearchData();
    }

    public function searchIndexShouldBeUpdated(): bool
    {
        return false;
    }

    public function isCloneable(): bool
    {
        return in_array($this->templateName(), self::STANDARD_TEMPLATES);
    }
}

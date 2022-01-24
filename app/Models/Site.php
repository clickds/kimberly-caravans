<?php

namespace App\Models;

use App\Presenters\SitePresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use McCool\LaravelAutoPresenter\HasPresenter;

class Site extends Model implements HasPresenter
{
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'country',
        'flag',
        'footer_content',
        'has_stock',
        'is_default',
        'display_exclusive_manufacturers_separately',
        'show_opening_times_and_telephone_number',
        'show_buy_tab_on_new_model_pages',
        'show_offers_tab_on_new_model_pages',
        'show_dealer_ranges',
        'show_live_chat',
        'show_social_icons',
        'show_accreditation_icons',
        'show_footer_content',
        'subdomain',
        'phone_number',
        'timezone',
        'campaign_monitor_list_id',
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'is_default' => 'boolean',
        'has_stock' => 'boolean',
        'show_opening_times_and_telephone_number' => 'boolean',
        'display_exclusive_manufacturers_separately' => 'boolean',
        'show_buy_tab_on_new_model_pages' => 'boolean',
        'show_offers_tab_on_new_model_pages' => 'boolean',
        'show_dealer_ranges' => 'boolean',
        'show_live_chat' => 'boolean',
        'show_social_icons' => 'boolean',
        'show_accreditation_icons' => 'boolean',
        'show_footer_content' => 'boolean',
    ];

    public function openingTimes(): HasMany
    {
        return $this->hasMany(OpeningTime::class);
    }

    public function currentOpeningTime(): OpeningTime
    {
        $day = Carbon::now($this->timezone)->dayOfWeek;
        $openingTime = $this->openingTimes()->forDay($this->timezone, $day)->first();
        if ($openingTime) {
            return $openingTime;
        }
        return new OpeningTime([
            // Purposefully set to ensure the isOpen method returns false
            'day' => $day,
            'opens_at' => '17:00',
            'closes_at' => '09:00',
        ]);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function brochures(): HasMany
    {
        return $this->hasMany(Brochure::class);
    }

    public function navigations(): HasMany
    {
        return $this->hasMany(Navigation::class);
    }

    public function events(): MorphToMany
    {
        return $this->morphedByMany(Event::class, 'pageable', 'pageable_site');
    }

    public function articles(): Relation
    {
        return $this->morphedByMany(Article::class, 'pageable', 'pageable_site');
    }

    public function manufacturers(): Relation
    {
        return $this->morphedByMany(Manufacturer::class, 'pageable', 'pageable_site');
    }

    public function caravanRanges(): Relation
    {
        return $this->morphedByMany(CaravanRange::class, 'pageable', 'pageable_site');
    }

    public function motorhomeRanges(): Relation
    {
        return $this->morphedByMany(MotorhomeRange::class, 'pageable', 'pageable_site');
    }

    public function scopeHasStock(Builder $query): Builder
    {
        return $query->where('has_stock', true);
    }

    public function getPresenterClass(): string
    {
        return SitePresenter::class;
    }

    public function locale(): string
    {
        switch ($this->flag) {
            case 'new-zealand.svg':
                return 'en-NZ';
            case 'ireland.svg':
                return 'en-IE';
            default:
                return 'en-GB';
        }
    }

    public function currencyCode(): string
    {
        switch ($this->flag) {
            case 'new-zealand.svg':
                return 'NZD';
            case 'ireland.svg':
                return 'EUR';
            default:
                return 'GBP';
        }
    }
}

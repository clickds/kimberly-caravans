<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Presenters\LogoPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use McCool\LaravelAutoPresenter\HasPresenter;

class Logo extends Model implements HasMedia, HasPresenter
{
    use InteractsWithMedia;

    public const DISPLAY_LOCATION_FOOTER = 'Footer';

    public const VALID_DISPLAY_LOCATIONS = [
        self::DISPLAY_LOCATION_FOOTER,
    ];

    /** @var array */
    protected $fillable = [
        'name',
        'external_url',
        'page_id',
        'display_location',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function setDisplayLocationAttribute(string $displayLocation): void
    {
        if (!in_array($displayLocation, self::VALID_DISPLAY_LOCATIONS)) {
            throw new InvalidArgumentException('Invalid display location');
        }

        $this->attributes['display_location'] = $displayLocation;
    }

    public function scopeFooterLocation(Builder $query): Builder
    {
        return $query->where('display_location', self::DISPLAY_LOCATION_FOOTER);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getPresenterClass(): string
    {
        return LogoPresenter::class;
    }
}

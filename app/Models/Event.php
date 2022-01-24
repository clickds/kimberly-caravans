<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Pageable;
use App\Presenters\EventPresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia, HasPresenter
{
    use Pageable;
    use InteractsWithMedia;
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'summary',
        'dealer_id',
        'event_location_id',
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function eventLocation(): BelongsTo
    {
        return $this->belongsTo(EventLocation::class);
    }

    public function scopeUpcoming(Builder $query, ?Carbon $date = null): Builder
    {
        if (is_null($date)) {
            $date = Carbon::today();
        }
        return $query->where('start_date', '>=', $date);
    }

    public function sluggableSources(): array
    {
        return [
            'name',
            'sluggable_start_date',
            'sluggable_end_date',
        ];
    }

    public function hasStartDate(): bool
    {
        return null !== $this->start_date;
    }

    public function hasEndDate(): bool
    {
        return null !== $this->end_date;
    }

    public function getSluggableStartDateAttribute(): string
    {
        if ($this->hasStartDate()) {
            return $this->start_date->format("d-m-Y");
        }
        return "";
    }

    public function getSluggableEndDateAttribute(): string
    {
        if ($this->hasEndDate()) {
            return $this->end_date->format("d-m-Y");
        }
        return "";
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('responsiveIndex')
            ->width(400)
            ->height(200)
            ->fit(Manipulations::FIT_FILL, 400, 200)
            ->crop(Manipulations::CROP_CENTER, 400, 200)
            ->performOnCollections('image')
            ->withResponsiveImages();

        $this->addMediaConversion('responsiveShow')
            ->width(640)
            ->height(640)
            ->fit(Manipulations::FIT_FILL, 640, 640)
            ->crop(Manipulations::CROP_CENTER, 640, 640)
            ->performOnCollections('image')
            ->withResponsiveImages();
    }

    public function getPresenterClass(): string
    {
        return EventPresenter::class;
    }
}

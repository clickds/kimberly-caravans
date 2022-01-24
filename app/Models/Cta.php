<?php

namespace App\Models;

use App\Presenters\Cta\EventTypePresenter;
use App\Presenters\Cta\StandardTypePresenter;
use App\Presenters\Cta\NewsAndInfoLanderTypePresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use UnexpectedValueException;

class Cta extends Model implements HasMedia, HasPresenter
{
    use InteractsWithMedia;
    use Ransackable;

    public const TYPE_EVENT = 'Event';
    public const TYPE_STANDARD = 'Standard';
    public const TYPE_NEWS_AND_INFO_LANDER = 'News and Info Lander';

    public const TYPES = [
        self::TYPE_EVENT,
        self::TYPE_STANDARD,
        self::TYPE_NEWS_AND_INFO_LANDER,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'excerpt_text',
        'link_text',
        'page_id',
        'position',
        'site_id',
        'title',
        'type',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @param mixed $value
     */
    public function setTypeAttribute($value): void
    {
        if (!in_array($value, self::TYPES)) {
            throw new UnexpectedValueException('Type value is invalid');
        }
        $this->attributes['type'] = $value;
    }

    public function getPresenterClass()
    {
        switch ($this->type) {
            case self::TYPE_EVENT:
                return EventTypePresenter::class;
            case self::TYPE_NEWS_AND_INFO_LANDER:
                return NewsAndInfoLanderTypePresenter::class;
            default:
                return StandardTypePresenter::class;
        }
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)->height(100)
            ->fit(Manipulations::FIT_FILL, 100, 100);

        $this->addMediaConversion('responsive-box-shadow')
            ->width(375)
            ->height(280)
            ->fit(Manipulations::FIT_CROP, 375, 280)
            ->withResponsiveImages()
            ->keepOriginalImageFormat();

        $this->addMediaConversion('responsive-standard')
            ->fit(Manipulations::FIT_CONTAIN, 375, 200)
            ->withResponsiveImages()
            ->keepOriginalImageFormat();
    }
}

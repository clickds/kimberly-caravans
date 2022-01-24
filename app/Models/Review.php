<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Publishable;
use App\Models\Traits\Expirable;
use App\Presenters\ReviewPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Review extends Model implements HasMedia, HasPresenter
{
    use Publishable;
    use Expirable;
    use InteractsWithMedia;
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'review_category_id',
        'date',
        'dealer_id',
        'expired_at',
        'link',
        'magazine',
        'position',
        'published_at',
        'title',
        'text',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReviewCategory::class, 'review_category_id');
    }

    public function caravanRanges(): BelongsToMany
    {
        return $this->belongsToMany(CaravanRange::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function motorhomeRanges(): BelongsToMany
    {
        return $this->belongsToMany(MotorhomeRange::class);
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('review_file')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)->height(80)
            ->performOnCollections('image');

        $this->addMediaConversion('responsiveIndex')
            ->fit(Manipulations::FIT_MAX, 200, 300)
            ->performOnCollections('image')
            ->withResponsiveImages()->nonQueued();
    }

    public function getPresenterClass(): string
    {
        return ReviewPresenter::class;
    }

    public function dealerName(): string
    {
        if (is_null($this->dealer)) {
            return "N/A";
        }
        return $this->dealer->name;
    }
}

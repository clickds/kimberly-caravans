<?php

namespace App\Models;

use App\Presenters\Page\BasePagePresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class StockSearchLink extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const TYPE_CARAVAN = 'Caravan';
    public const TYPE_MOTORHOME = 'Motorhome';
    public const TYPES = [
        self::TYPE_CARAVAN,
        self::TYPE_MOTORHOME,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'site_id',
        'page_id',
    ];

    public function scopeForSite(Builder $query, Site $site): Builder
    {
        return $query->where('site_id', $site->id);
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', 'image');
    }

    public function mobileImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', 'mobile-image');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('mobile-image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)->height(100);

        $this->addMediaConversion('responsive')
            ->fit(Manipulations::FIT_MAX, 640, 200)
            ->withResponsiveImages()
            ->keepOriginalImageFormat()
            ->nonQueued()
            ->performOnCollections('image');
    }

    public function jsonSerialize(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($this->page) {
            $pagePresenter = new BasePagePresenter();
            $pagePresenter->setWrappedObject($this->page);
            $data['link'] = $pagePresenter->link();
        }

        if ($image = $this->image) {
            $imageDetails = [
                'alt' => $image->name,
                'srcSet' => $image->getSrcSet('responsive'),
                'src' => $image->getUrl('responsive'),
                'width' => $image->responsiveImages('responsive')->files->first()->width(),
            ];

            $data['image'] = $imageDetails;
        }

        if ($mobileImage = $this->mobileImage) {
            $mobileImageDetails = [
                'alt' => $mobileImage->name,
                'src' => $mobileImage->getUrl(),
            ];

            $data['mobile_image'] = $mobileImageDetails;
        }

        return $data;
    }
}

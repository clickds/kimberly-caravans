<?php

namespace App\Models;

use App\Presenters\UsefulLinkPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UsefulLink extends Model implements HasMedia, HasPresenter
{
    use InteractsWithMedia;
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
        'url',
        'useful_link_category_id',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'position' => 0,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(UsefulLinkCategory::class, 'useful_link_category_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)->height(80);

        $this->addMediaConversion('responsiveIndex')
            ->fit(Manipulations::FIT_CONTAIN, 250, 250)
            ->performOnCollections('image')
            ->withResponsiveImages()->nonQueued();
    }

    public function getPresenterClass(): string
    {
        return UsefulLinkPresenter::class;
    }

    public function categoryName(): string
    {
        if ($category = $this->category) {
            return $category->name;
        }
        return "";
    }
}

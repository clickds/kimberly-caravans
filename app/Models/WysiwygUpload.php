<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jdexx\EloquentRansack\Ransackable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WysiwygUpload extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('responsive')
            ->performOnCollections('file')
            ->withResponsiveImages()
            ->keepOriginalImageFormat()
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->width(80)
            ->height(80);
    }
}

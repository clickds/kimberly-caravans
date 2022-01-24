<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DealerEmployee extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'role',
        'phone',
        'email',
        'position',
    ];

    public function dealer(): Relation
    {
        return $this->belongsTo(Dealer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)
            ->height(80);

        $this->addMediaConversion('responsiveIndex')
            ->width(400)
            ->height(200)
            ->fit(Manipulations::FIT_FILL, 400, 200)
            ->crop(Manipulations::CROP_CENTER, 400, 200)
            ->performOnCollections('image')
            ->withResponsiveImages();
    }
}

<?php

namespace App\Models;

use App\Models\Traits\Expirable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jdexx\EloquentRansack\Ransackable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VideoBanner extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Ransackable;
    use Publishable;
    use Expirable;
    use Liveable;

    /**
     * @var array $fillable;
     */
    protected $fillable = [
        'name',
        'published_at',
        'expired_at',
        'live',
    ];

    protected $casts = [
        'live' => 'bool',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('mp4')->singleFile();
        $this->addMediaCollection('webm')->singleFile();
    }
}

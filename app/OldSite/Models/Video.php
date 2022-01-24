<?php

namespace App\OldSite\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $image_file_name
 * @property string $name
 * @property string $embed_markup
 * @property string $date_live
 * @property string $teaser
 */
class Video extends BaseModel
{
    /**
     * Get all of the news article's panels.
     */
    public function panels(): MorphMany
    {
        return $this->morphMany(Panel::class, 'document');
    }

    public function mediaCategories(): BelongsToMany
    {
        return $this->belongsToMany(MediaCategory::class, 'media_categories_videos');
    }

    /**
     * Override the class laravel uses in the morphMany query
     */
    public function getMorphClass(): string
    {
        return 'Video';
    }
}

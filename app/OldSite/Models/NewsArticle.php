<?php

namespace App\OldSite\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $date_live
 * @property string $created_at
 * @property string $updated_at
 * @property string $headline
 * @property string $teaser
 * @property string $image_file_name
 */
class NewsArticle extends BaseModel
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
        return $this->belongsToMany(MediaCategory::class, 'media_categories_news_articles');
    }

    /**
     * Override the class laravel uses in the morphMany query
     */
    public function getMorphClass(): string
    {
        return 'NewsArticle';
    }
}

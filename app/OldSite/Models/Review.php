<?php

namespace App\OldSite\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $date_live
 * @property string $date_expires
 * @property string $source_url
 * @property string $source
 * @property string $name
 * @property string $teaser
 * @property string $image_file_name
 * @property string $pdf_file_name
 */
class Review extends BaseModel
{
    public function mediaCategories(): BelongsToMany
    {
        return $this->belongsToMany(MediaCategory::class, 'media_categories_reviews');
    }
}

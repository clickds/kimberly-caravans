<?php

namespace App\OldSite\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $sort_order
 * @property string $copy
 */
class Panel extends BaseModel
{
    public const PANEL_TYPES = [
        'RichText',
        'Form',
        'Offers',
        'News',
        'Videos',
        'Reviews',
        'Events',
        'Calc',
        'Advent',
        'Brochure',
    ];

    public function scopeImportable(Builder $query): Builder
    {
        return $query->where('area', '!=', 'Hidden')
            ->where(function ($query) {
                return $query->where('panel_type', 'RichText')
                    ->orWhere(function ($query) {
                        $query->where('panel_type', 'Videos')
                            ->whereNotNull('video_id');
                    });
            });
    }

    public function document(): MorphTo
    {
        return $this->morphTo();
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}

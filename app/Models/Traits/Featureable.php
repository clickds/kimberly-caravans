<?php

namespace App\Models\Traits;

use App\Models\Panel;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * A Featureable is an item that is associated with a panel
 */
trait Featureable
{
    public static function bootFeatureable(): void
    {
        static::deleting(function ($model) {
            // Delete all the featurables panels
            $model->panels()->delete();
        });
    }

    public function panels(): Relation
    {
        return $this->morphMany(Panel::class, 'featureable');
    }
}

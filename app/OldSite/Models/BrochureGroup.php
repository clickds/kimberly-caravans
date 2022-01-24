<?php

namespace App\OldSite\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $sort_order
 * @property string $name
 */
class BrochureGroup extends BaseModel
{
    public function brochures(): HasMany
    {
        return $this->hasMany(Brochure::class);
    }
}

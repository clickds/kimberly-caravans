<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jdexx\EloquentRansack\Ransackable;

class BrochureGroup extends Model
{
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'position' => 0,
    ];

    public function brochures(): HasMany
    {
        return $this->hasMany(Brochure::class, 'group_id');
    }
}

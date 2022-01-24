<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsefulLinkCategory extends Model
{
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

    public function usefulLinks(): HasMany
    {
        return $this->hasMany(UsefulLink::class);
    }
}

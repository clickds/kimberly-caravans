<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jdexx\EloquentRansack\Ransackable;

class Fieldset extends Model
{
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'content',
        'name',
    ];

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}

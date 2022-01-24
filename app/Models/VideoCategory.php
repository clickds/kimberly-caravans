<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    /**
     * @var array $fillable;
     */
    protected $fillable = [
        'name',
        'position',
    ];
}

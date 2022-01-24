<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Publishable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jdexx\EloquentRansack\Ransackable;

class Testimonial extends Model
{
    use Publishable;
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'content',
        'name',
        'position',
        'published_at',
    ];

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'site_testimonial');
    }
}

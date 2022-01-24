<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jdexx\EloquentRansack\Ransackable;

class Alias extends Model
{
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'capture_path',
        'page_id',
        'site_id',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}

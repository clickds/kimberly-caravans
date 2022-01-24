<?php

namespace App\Models;

use App\Presenters\NavigationItemPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\HasPresenter;

class NavigationItem extends Model implements HasPresenter
{
    /**
     * Note this is an associative array of tailwind colour name => humanised name
     */
    public const BACKGROUND_COLOURS = [
        'shiraz' => 'Burgundy',
        'white' => 'White',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'display_order',
        'page_id',
        'query_parameters',
        'external_url',
        'background_colour',
    ];

    public function navigation(): BelongsTo
    {
        return $this->belongsTo(Navigation::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavigationItem::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function scopeDisplayable(Builder $query, Collection $pageIds): Builder
    {
        return $query->where(function ($query) use ($pageIds) {
            $query->whereNull('page_id');

            if ($pageIds->isNotEmpty()) {
                $query->orWhereIn('page_id', $pageIds);
            }

            return $query;
        });
    }

    public function getPresenterClass(): string
    {
        return NavigationItemPresenter::class;
    }
}

<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use App\Models\Page;
use App\Models\Site;
use App\Models\PageableSite;
use App\Events\PageableUpdated;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;

trait Pageable
{
    public static function bootPageable(): void
    {
        static::saved(function ($model) {
            $event = new PageableUpdated($model);
            event($event);
        });
        static::deleting(function ($model) {
            // Delete all the pageables pages
            $model->pages()->delete();
            // Detach all sites from the pageable
            $model->sites()->detach();
        });
    }

    abstract public function sluggableSources(): array;

    /**
     * Get all of the pages for the pageable.
     */
    public function pages(): MorphMany
    {
        return $this->morphMany(Page::class, 'pageable');
    }

    public function pageableUrlParameter(): string
    {
        $parameter = class_basename($this) . '-' . $this->getKey();
        return Str::slug($parameter);
    }

    /**
     * Get all of the sites for the pageable.
     */
    public function sites(): MorphToMany
    {
        return $this->morphToMany(Site::class, 'pageable', 'pageable_site')->withPivot([
            'price',
            'recommended_price',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Page>|\App\Models\Page|null
     */
    public function sitePage(Site $site)
    {
        if (!$this->relationLoaded('pages')) {
            return $this->pages()->where('site_id', $site->id)->first();
        }
        if (empty($this->pages)) {
            return null;
        }
        return $this->pages->firstWhere('site_id', $site->id);
    }
}

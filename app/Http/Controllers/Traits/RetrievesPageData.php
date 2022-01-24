<?php

namespace App\Http\Controllers\Traits;

use App\Models\ImageBanner;
use App\Models\Page;
use App\Models\VideoBanner;
use Illuminate\Database\Eloquent\Collection;

trait RetrievesPageData
{
    private function fetchPages(Page $currentPage = null): Collection
    {
        $query = Page::query();
        if ($currentPage) {
            $query->where('id', '!=', $currentPage->id);
        }
        return $query->get();
    }

    private function fetchTemplates(): array
    {
        return Page::STANDARD_TEMPLATES;
    }

    private function fetchVarieties(): array
    {
        return Page::VARIETIES;
    }

    private function fetchVideoBanners(): Collection
    {
        return VideoBanner::orderBy('name', 'asc')->select('id', 'name')->get();
    }

    private function fetchImageBanners(): Collection
    {
        return ImageBanner::orderBy('title', 'asc')->select('id', 'title')->get();
    }
}

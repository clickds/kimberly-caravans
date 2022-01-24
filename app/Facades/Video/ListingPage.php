<?php

namespace App\Facades\Video;

use App\Facades\BasePage;
use App\Models\Dealer;
use App\Models\Page;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;

class ListingPage extends BasePage
{
    private LengthAwarePaginator $videos;
    private EloquentCollection $videoCategories;
    private EloquentCollection $dealers;
    private Collection $rangeNames;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->videos = $this->fetchVideos();
        $this->videoCategories = $this->fetchVideoCategories();
        $this->dealers = $this->fetchDealers();
        $this->rangeNames = $this->fetchRangeNames();
    }

    public function getVideos(): LengthAwarePaginator
    {
        return $this->videos;
    }

    private function fetchVideos(): LengthAwarePaginator
    {
        $request = $this->getRequest();
        $query = $this->baseQuery();

        // Filter by inputs that have columns on videos
        $query->ransack($request->all());
        $videoCategoryIds = array_filter($request->get('video_category_ids', []));
        $rangeNames = array_filter($request->get('range_names', []));

        if (!empty($videoCategoryIds)) {
            $query->whereHas('videoCategories', function ($query) use ($videoCategoryIds) {
                return $query->whereIn('id', $videoCategoryIds);
            });
        }

        if (!empty($rangeNames)) {
            $query->where(function ($query) use ($rangeNames) {
                $query->whereHas('caravanRanges', function ($query) use ($rangeNames) {
                    $query->whereIn('name', $rangeNames);
                })->orWhereHas('motorhomeRanges', function ($query) use ($rangeNames) {
                    $query->whereIn('name', $rangeNames);
                });
            });
        }

        return $query->distinct()->select('videos.*')
            ->orderBy('published_at', 'desc')->paginate($this->perPage());
    }

    private function baseQuery(): Builder
    {
        $videoIds = $this->displayableVideoIds();

        return Video::with('media')
            ->with([
                'pages' => function ($query) {
                    $query->forSite($this->getSite())
                        ->with('parent:id,slug')
                        ->select('id', 'parent_id', 'slug', 'site_id', 'pageable_type', 'pageable_id');
                },
            ])
            ->published()->whereIn('videos.id', $videoIds);
    }

    private function displayableVideoIds(): Collection
    {
        return Page::forSite($this->getSite())
            ->where('pageable_type', Video::class)
            ->displayable()->toBase()->pluck('pageable_id');
    }

    public function getVideoCategories(): EloquentCollection
    {
        return $this->videoCategories;
    }

    public function getDealers(): EloquentCollection
    {
        return $this->dealers;
    }

    public function getRangeNames(): Collection
    {
        return $this->rangeNames;
    }

    private function fetchVideoCategories(): EloquentCollection
    {
        return VideoCategory::orderBy('position', 'asc')->select('id', 'name')->get();
    }

    private function fetchDealers(): EloquentCollection
    {
        $dealerIds = $this->fetchDealerIds();

        return Dealer::where('site_id', $this->getSite()->id)
            ->whereIn('id', $dealerIds)
            ->orderBy('name', 'asc')->get();
    }

    private function fetchDealerIds(): Collection
    {
        return Video::join('pageable_site', 'videos.id', '=', 'pageable_site.pageable_id')
            ->where('pageable_site.site_id', $this->getSite()->id)
            ->distinct()
            ->toBase()->pluck('dealer_id');
    }

    private function fetchRangeNames(): Collection
    {
        $caravanRangesQuery = DB::table('caravan_ranges')->select('name');

        return DB::table('motorhome_ranges')->select('name')
            ->union($caravanRangesQuery)
            ->distinct()
            ->orderBy('name', 'asc')
            ->pluck('name');
    }
}

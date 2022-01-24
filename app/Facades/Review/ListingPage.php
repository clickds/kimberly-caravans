<?php

namespace App\Facades\Review;

use App\Facades\BasePage;
use App\Models\Dealer;
use App\Models\Page;
use App\Models\Review;
use App\Models\ReviewCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ListingPage extends BasePage
{
    private Paginator $reviews;
    private EloquentCollection $reviewCategories;
    private EloquentCollection $dealers;
    private Collection $rangeNames;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->reviews = $this->fetchReviews();
        $this->reviewCategories = $this->fetchReviewCategories();
        $this->dealers = $this->fetchDealers();
        $this->rangeNames = $this->fetchRangeNames();
    }

    public function getReviews(): Paginator
    {
        return $this->reviews;
    }

    public function getReviewCategories(): EloquentCollection
    {
        return $this->reviewCategories;
    }

    public function getDealers(): EloquentCollection
    {
        return $this->dealers;
    }

    public function getRangeNames(): Collection
    {
        return $this->rangeNames;
    }

    private function fetchReviews(): Paginator
    {
        $request = $this->getRequest();
        $query = Review::published()->whereHas('sites', function ($query) {
            $query->where('id', $this->getSite()->id);
        });
        $query->ransack($request->all());

        $rangeNames = array_filter($request->get('range_names', []));
        if (!empty($rangeNames)) {
            $query->join('caravan_range_review', 'reviews.id', '=', 'caravan_range_review.review_id')
                ->join('caravan_ranges', 'caravan_range_review.caravan_range_id', '=', 'caravan_ranges.id')
                ->join('motorhome_range_review', 'reviews.id', '=', 'motorhome_range_review.review_id')
                ->join('motorhome_ranges', 'motorhome_range_review.motorhome_range_id', '=', 'motorhome_ranges.id')
                ->where(function ($query) use ($rangeNames) {
                    $query->whereIn('caravan_ranges.name', $rangeNames)
                        ->orWhereIn('motorhome_ranges.name', $rangeNames);
                })
                ->distinct();
        }

        return $query->orderBy('date', 'desc')->select('reviews.*')->paginate($this->perPage());
    }

    public function fetchReviewCategories(): EloquentCollection
    {
        return ReviewCategory::all();
    }

    private function fetchDealers(): EloquentCollection
    {
        return Dealer::select('id', 'name')->where('site_id', $this->getSite()->id)->get();
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

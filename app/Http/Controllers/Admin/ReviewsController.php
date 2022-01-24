<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use App\Models\ReviewCategory;
use App\Http\Requests\Admin\Reviews\StoreRequest;
use App\Http\Requests\Admin\Reviews\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewsController extends BaseController
{
    public function index(Request $request): View
    {
        $reviews = Review::with('category')->ransack($request->all())
            ->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'categories' => ReviewCategory::orderBy('name', 'asc')->get(),
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_REVIEWS_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.reviews.create', [
            'categories' => ReviewCategory::orderBy('name', 'asc')->get(),
            'caravanRangeIds' => [],
            'motorhomeRangeIds' => [],
            'caravanRanges' => $this->fetchCaravanRanges(),
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'dealers' => $this->fetchDealers(),
            'review' => new Review(),
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $review = new Review();
        if ($this->saveReview($review, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Review created');
            }

            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Review created');
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to create review');
    }

    public function edit(Review $review, Request $request): View
    {
        return view('admin.reviews.edit', [
            'categories' => ReviewCategory::orderBy('name', 'asc')->get(),
            'caravanRangeIds' => $review->caravanRanges()->pluck('id')->toArray(),
            'motorhomeRangeIds' => $review->motorhomeRanges()->pluck('id')->toArray(),
            'caravanRanges' => $this->fetchCaravanRanges(),
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'dealers' => $this->fetchDealers(),
            'sites' => $this->fetchSites(),
            'review' => $review,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Review $review): RedirectResponse
    {
        if ($this->saveReview($review, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Review updated');
            }

            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Review updated');
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to update review');
    }

    public function destroy(Review $review, Request $request): RedirectResponse
    {
        $review->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Review deleted');
        }

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted');
    }

    private function saveReview(Review $review, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $review->fill($request->validated());
            $review->save();
            $review->caravanRanges()->sync($request->input('caravan_range_ids', []));
            $review->motorhomeRanges()->sync($request->input('motorhome_range_ids', []));
            $review->sites()->sync($request->input('site_ids', []));
            $this->addImage($request, $review);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }

    private function addImage(FormRequest $request, Review $review): void
    {
        if ($request->hasFile('image')) {
            $review->addMediaFromRequest('image')->toMediaCollection('image');
        }
        if ($request->hasFile('review_file')) {
            $review->addMediaFromRequest('review_file')->toMediaCollection('review_file');
        }
    }

    private function fetchCaravanRanges(): Collection
    {
        return CaravanRange::with('manufacturer:id,name')->orderBy('name', 'asc')
            ->select('name', 'id', 'manufacturer_id')->get();
    }

    private function fetchDealers(): Collection
    {
        return Dealer::orderBy('name', 'asc')->select('name', 'id')->get();
    }

    private function fetchMotorhomeRanges(): Collection
    {
        return MotorhomeRange::with('manufacturer:id,name')->orderBy('name', 'asc')
            ->select('name', 'id', 'manufacturer_id')->get();
    }
}

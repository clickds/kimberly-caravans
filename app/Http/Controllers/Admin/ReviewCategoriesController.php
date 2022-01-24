<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ReviewCategories\StoreRequest;
use App\Http\Requests\Admin\ReviewCategories\UpdateRequest;
use App\Models\ReviewCategory;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewCategoriesController extends BaseController
{
    public function index(Request $request): View
    {
        $reviewCategories = ReviewCategory::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.review-categories.index', [
            'reviewCategories' => $reviewCategories,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function create(): View
    {
        return view('admin.review-categories.create', ['reviewCategory' => new ReviewCategory()]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            ReviewCategory::create($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return back()
                ->withInput($request->all())
                ->with('error', 'Failed to create review category');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Review category created');
        }

        return redirect()
            ->route('admin.review-categories.index')
            ->with('success', 'Review category created');
    }

    public function edit(ReviewCategory $reviewCategory, Request $request): View
    {
        return view('admin.review-categories.edit', [
            'reviewCategory' => $reviewCategory,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, ReviewCategory $reviewCategory): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $reviewCategory->update($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return back()
                ->withInput($request->all())
                ->with('error', 'Failed to update review category');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Review category updated');
        }

        return redirect()
            ->route('admin.review-categories.index')
            ->with('success', 'Review category updated');
    }

    public function destroy(ReviewCategory $reviewCategory, Request $request): RedirectResponse
    {
        $reviewCategory->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Review category deleted');
        }

        return redirect()
            ->route('admin.review-categories.index')
            ->with('success', 'Review category deleted');
    }
}

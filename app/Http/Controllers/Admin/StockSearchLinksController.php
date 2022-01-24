<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StockSearchLinks\StoreRequest;
use App\Http\Requests\Admin\StockSearchLinks\UpdateRequest;
use App\Models\Site;
use App\Models\StockSearchLink;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StockSearchLinksController extends BaseController
{
    public function index(): View
    {
        $stockSearchLinks = StockSearchLink::orderBy('name', 'asc')->get();

        return view('admin.stock-search-links.index', [
            'stockSearchLinks' => $stockSearchLinks,
        ]);
    }

    public function create(Request $request): View
    {
        $stockSearchLink = new StockSearchLink();

        return view('admin.stock-search-links.create', [
            'stockSearchLink' => $stockSearchLink,
            'sites' => $this->fetchSites(),
            'types' => $this->fetchTypes(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $stockSearchLink = new StockSearchLink();

        if ($this->saveStockSearchLink($stockSearchLink, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Stock search link created');
            }

            return redirect()
                ->route('admin.stock-search-links.index')
                ->with('success', 'Stock search link created');
        }

        return redirect()
            ->back()
            ->with('error', 'Failed to save stock search link');
    }

    public function edit(StockSearchLink $stockSearchLink, Request $request): View
    {
        return view('admin.stock-search-links.edit', [
            'stockSearchLink' => $stockSearchLink,
            'sites' => $this->fetchSites(),
            'types' => $this->fetchTypes(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, StockSearchLink $stockSearchLink): RedirectResponse
    {
        if ($this->saveStockSearchLink($stockSearchLink, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Stock search link updated');
            }

            return redirect()
                ->route('admin.stock-search-links.index')
                ->with('success', 'Stock search link updated');
        }

        return redirect()
            ->back()
            ->with('error', 'Failed to update stock search link');
    }

    public function destroy(StockSearchLink $stockSearchLink, Request $request): RedirectResponse
    {
        $stockSearchLink->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Stock search link deleted');
        }

        return redirect()
            ->route('admin.stock-search-links.index')
            ->with('success', 'Stock search link deleted');
    }

    private function saveStockSearchLink(StockSearchLink $stockSearchLink, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $stockSearchLink->fill($request->validated());
            $stockSearchLink->save();

            if ($request->hasFile('image')) {
                $stockSearchLink->addMediaFromRequest('image')->toMediaCollection('image');
            }

            if ($request->hasFile('mobile_image')) {
                $stockSearchLink->addMediaFromRequest('mobile_image')->toMediaCollection('mobile-image');
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    protected function fetchSites(array $ignoreIds = []): Collection
    {
        return Site::hasStock()->get();
    }

    private function fetchTypes(): array
    {
        return StockSearchLink::TYPES;
    }
}

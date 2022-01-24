<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Assets\StoreRequest;
use App\Models\WysiwygUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssetsController extends BaseController
{
    public function index(Request $request): View
    {
        $assets = WysiwygUpload::with('media')->ransack($request->all())
            ->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.assets.index', [
            'assets' => $assets,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.assets.create', [
            'asset' => new WysiwygUpload(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        if ($this->saveAsset($request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Asset created');
            }

            return redirect()->route('admin.assets.index')->with('success', 'Asset created');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('info', 'Failed to save asset');
    }

    public function destroy(WysiwygUpload $asset, Request $request): RedirectResponse
    {
        $asset->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Asset deleted');
        }

        return redirect()
            ->route('admin.assets.index')
            ->with('success', 'Asset deleted');
    }

    private function saveAsset(StoreRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $asset = WysiwygUpload::create($request->validated());
            $asset->addMediaFromRequest('file')
                ->usingName($request->get('name'))
                ->toMediaCollection('file');
            DB::commit();

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return false;
        }
    }
}

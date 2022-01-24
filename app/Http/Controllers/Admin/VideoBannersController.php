<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\VideoBanners\StoreRequest;
use App\Http\Requests\Admin\VideoBanners\UpdateRequest;
use Illuminate\Support\Arr;
use App\Models\VideoBanner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VideoBannersController extends BaseController
{
    public function index(Request $request): View
    {
        $videoBanners = VideoBanner::with('pages:id,slug,name', 'pages.parent:id,slug')->ransack($request->all())
            ->orderBy('name', 'asc')->paginate(15);

        return view('admin.video-banners.index', [
            'videoBanners' => $videoBanners,
        ]);
    }

    public function create(Request $request): View
    {
        $videoBanner = new VideoBanner();

        return view('admin.video-banners.create', [
            'videoBanner' => $videoBanner,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $videoBanner = VideoBanner::create($request->validated());
            $this->addVideoFiles($videoBanner, $request);
            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Video banner created');
            }

            return redirect()
                ->route('admin.video-banners.index')
                ->with('success', 'Video banner created');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()
                ->back()
                ->with('warning', 'Failed to create video banner');
        }
    }

    public function edit(VideoBanner $videoBanner, Request $request): View
    {
        return view('admin.video-banners.edit', [
            'videoBanner' => $videoBanner,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, VideoBanner $videoBanner): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $videoBanner->update($request->validated());
            $this->addVideoFiles($videoBanner, $request);
            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Video banner updated');
            }

            return redirect()
                ->route('admin.video-banners.index')
                ->with('success', 'Video banner updated');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()
                ->back()
                ->with('warning', 'Failed to update video banner');
        }
    }

    public function destroy(VideoBanner $videoBanner, Request $request): RedirectResponse
    {
        $videoBanner->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Video banner deleted');
        }

        return redirect()
            ->route('admin.video-banners.index')
            ->with('success', 'Video banner deleted');
    }

    private function addVideoFiles(VideoBanner $videoBanner, FormRequest $request): void
    {
        $files = [
            'mp4' => 'mp4',
            'webm' => 'webm',
        ];
        foreach ($files as $inputName => $collection) {
            if ($request->hasFile($inputName)) {
                $videoBanner->addMediaFromRequest($inputName)->toMediaCollection($collection);
            }
        }
    }
}

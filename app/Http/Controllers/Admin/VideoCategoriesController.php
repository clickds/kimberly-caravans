<?php

namespace App\Http\Controllers\Admin;

use App\Models\VideoCategory;
use App\Http\Requests\Admin\VideoCategories\StoreRequest;
use App\Http\Requests\Admin\VideoCategories\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoCategoriesController extends BaseController
{
    public function index(): View
    {
        $video_categories = VideoCategory::orderBy('name', 'asc')->get();

        return view('admin.video-categories.index')->with('video_categories', $video_categories);
    }

    public function create(Request $request): View
    {
        // load the create form
        return view('admin.video-categories.create', [
            'video_category' => new VideoCategory(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        VideoCategory::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Video category created');
        }

        return redirect()
            ->route('admin.video-categories.index')
            ->with('success', 'Video category created');
    }

    public function edit(VideoCategory $video_category, Request $request): View
    {
        return view('admin.video-categories.edit', [
            'video_category'  => $video_category,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, VideoCategory $video_category): RedirectResponse
    {
        $data = $request->validated();

        $video_category->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Video category updated');
        }

        return redirect()
            ->route('admin.video-categories.index')
            ->with('success', 'Video category updated');
    }

    public function destroy(VideoCategory $video_category, Request $request): RedirectResponse
    {
        $video_category->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Video category deleted');
        }

        return redirect()
            ->route('admin.video-categories.index')
            ->with('warning', 'Video Category deleted');
    }
}

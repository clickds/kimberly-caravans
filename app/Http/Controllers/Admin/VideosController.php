<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Videos\StoreRequest;
use App\Http\Requests\Admin\Videos\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Site;
use App\Services\Pageable\VideoPageSaver;
use App\Models\Traits\ImageDeletable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideosController extends BaseController
{
    use ImageDeletable;

    public function index(Request $request): View
    {
        $videos = Video::with('pages')->ransack($request->all());
        $categoryId = $request->input('video_category_id');
        if (!empty($categoryId)) {
            $videos->whereHas('videoCategories', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            });
        }
        $videos = $videos->orderBy('created_at', 'desc')->paginate(10);

        $categories = VideoCategory::orderBy('name', 'asc')->get();

        return view('admin.videos.index', [
            'videos' => $videos,
            'categories' => $categories,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_VIDEOS_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.videos.create', [
            'video' => new Video(),
            'categories' => $this->fetchCategories(),
            'sites' => $this->fetchSites(),
            'types' => Video::VALID_TYPES,
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'caravanRanges' => $this->fetchCaravanRanges(),
            'dealers' => $this->fetchDealers(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $video = new Video();

        if ($this->saveVideo($request, $video)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Video created');
            }

            return redirect()
                ->route('admin.videos.index')
                ->with('success', 'Video created');
        }

        return back()
            ->with('warning', 'Failed to create video')
            ->withInput($request->all());
    }

    public function edit(Video $video, Request $request): View
    {
        return view('admin.videos.edit', [
            'video' => $video->load('motorhomeRanges', 'caravanRanges'),
            'categories' => $this->fetchCategories(),
            'sites' => $this->fetchSites(),
            'types' => Video::VALID_TYPES,
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'caravanRanges' => $this->fetchCaravanRanges(),
            'dealers' => $this->fetchDealers(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Video $video): RedirectResponse
    {
        if ($this->saveVideo($request, $video)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Video updated');
            }

            return redirect()
                ->route('admin.videos.index')
                ->with('success', 'Video updated');
        }

        return back()
            ->with('warning', 'Failed to update video')
            ->withInput($request->all());
    }

    public function destroy(Video $video, Request $request): RedirectResponse
    {
        $video->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Video deleted');
        }

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Video deleted');
    }

    private function saveVideo(FormRequest $request, Video $video): bool
    {
        try {
            DB::beginTransaction();

            $video->fill($request->validated());
            $video->save();

            $this->addImage($request, $video);
            $this->syncSites($request, $video);
            $this->syncCaravanRanges($request, $video);
            $this->syncMotorhomeRanges($request, $video);
            $this->syncCategories($request, $video);
            $this->maintainSitePages($request, $video);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function syncCategories(FormRequest $request, Video $video): void
    {
        $categoryIds = $request->input('video_category_ids', []);
        $video->videoCategories()->sync($categoryIds);
    }

    private function addImage(FormRequest $request, Video $video): void
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $video->addMediaFromRequest('image')->toMediaCollection('image');
    }

    private function syncSites(FormRequest $request, Video $video): void
    {
        $video->sites()->sync(
            $request->get('site_ids', [])
        );
    }

    private function syncMotorhomeRanges(FormRequest $request, Video $video): void
    {
        $video->motorhomeRanges()->sync(
            $request->get('motorhome_range_ids', [])
        );
    }

    private function syncCaravanRanges(FormRequest $request, Video $video): void
    {
        $video->caravanRanges()->sync(
            $request->get('caravan_range_ids', [])
        );
    }

    private function maintainSitePages(FormRequest $request, Video $video): void
    {
        $siteIds = $request->get('site_ids', []);

        $video->pages()->whereNotIn('site_id', $siteIds)->delete();

        $sites = Site::whereIn('id', $siteIds)->get();

        foreach ($sites as $site) {
            $saver = new VideoPageSaver($video, $site);
            $saver->call();
        }
    }

    private function fetchCategories(): Collection
    {
        return VideoCategory::all();
    }

    private function fetchMotorhomeRanges(): Collection
    {
        return MotorhomeRange::all();
    }

    private function fetchCaravanRanges(): Collection
    {
        return CaravanRange::all();
    }

    private function fetchDealers(): Collection
    {
        return Dealer::all();
    }
}

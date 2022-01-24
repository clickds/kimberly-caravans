<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\RetrievesPageData;
use App\Http\Requests\Admin\Pages\StoreRequest;
use App\Http\Requests\Admin\Pages\UpdateRequest;
use App\Models\Page;
use App\Models\Traits\ImageDeletable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PagesController extends BaseController
{
    use ImageDeletable;
    use RetrievesPageData;

    public function index(Request $request): View
    {
        return view('admin.pages.index', [
            'pages' => Page::ransack($request->all())->with('parent', 'site')->withCount('areas')->paginate(15),
            'sites' => $this->fetchSites(),
            'templates' => Page::allTemplates(),
            'varieties' => $this->fetchVarieties(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.pages.create', [
            'page' => new Page(),
            'pages' => $this->fetchPages(),
            'sites' => $this->fetchSites(),
            'templates' => $this->fetchTemplates(),
            'varieties' => $this->fetchVarieties(),
            'imageBanners' => $this->fetchImageBanners(),
            'videoBanners' => $this->fetchVideoBanners(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function show(Page $page): View
    {
        return view('admin.pages.show', [
            'page' => $page,
        ]);
    }

    public function edit(Page $page, Request $request): View
    {
        return view('admin.pages.edit', [
            'page' => $page,
            'pages' => $this->fetchPages($page),
            'sites' => $this->fetchSites(),
            'templates' => $this->fetchTemplates(),
            'varieties' => $this->fetchVarieties(),
            'imageBanners' => $this->fetchImageBanners(),
            'videoBanners' => $this->fetchVideoBanners(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $page = new Page();

        if ($this->savePage($page, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Page saved');
            }

            return redirect()->route('admin.pages.index')->with('success', 'Page saved');
        }

        return redirect()->back()->withInput($request->all())->with('danger', 'Page could not be saved');
    }

    public function update(Page $page, UpdateRequest $request): RedirectResponse
    {
        $this->savePage($page, $request);

        if ($this->savePage($page, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Page updated');
            }

            return redirect()->route('admin.pages.index')->with('success', 'Page updated');
        }

        return redirect()->back()->withInput($request->all())->with('danger', 'Page could not be saved');
    }

    public function destroy(Page $page, Request $request): RedirectResponse
    {
        $page->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Page deleted');
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted');
    }

    private function savePage(Page $page, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $imageBannerIds = $request->get('image_banner_ids', []);

            $page->fill($data);

            // This will regenerate the slug if the page name has changed.
            if ($page->isDirty('name')) {
                $page->slug = null;
            }

            $page->save();

            $page->imageBanners()->sync($imageBannerIds);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }
}

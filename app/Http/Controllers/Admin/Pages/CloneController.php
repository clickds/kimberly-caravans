<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Traits\RetrievesPageData;
use App\Http\Requests\Admin\Pages\Clones\StoreRequest;
use App\Models\Page;
use App\Services\Page\PageCloner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CloneController extends BaseController
{
    use RetrievesPageData;

    public function create(Page $page, Request $request): View
    {
        return view('admin.pages.clones.create', [
            'page' => $page,
            'pages' => $this->fetchPages(),
            'sites' => $this->fetchSites(),
            'templates' => $this->fetchTemplates(),
            'varieties' => $this->fetchVarieties(),
            'imageBanners' => $this->fetchImageBanners(),
            'videoBanners' => $this->fetchVideoBanners(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(Page $page, StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $pageCloner = new PageCloner($page, $request->validated());

            $pageCloner->clone();

            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Page cloned');
            }

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Page cloned');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to clone page');
        }
    }
}

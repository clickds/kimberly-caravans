<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UsefulLinks\StoreRequest;
use App\Http\Requests\Admin\UsefulLinks\UpdateRequest;
use App\Models\Page;
use App\Models\UsefulLink;
use App\Models\UsefulLinkCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class UsefulLinksController extends BaseController
{
    public function index(Request $request): View
    {
        $usefulLinks = UsefulLink::with('category', 'media')
            ->ransack($request->all())
            ->orderBy('position', 'asc')->get();

        return view('admin.useful-links.index', [
            'usefulLinks' => $usefulLinks,
            'categories' => $this->usefulLinkCategories(),
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_USEFUL_LINK_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        $usefulLink = new UsefulLink();

        return view('admin.useful-links.create', [
            'usefulLink' => $usefulLink,
            'usefulLinkCategories' => $this->usefulLinkCategories(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $usefulLink = new UsefulLink();

        if ($this->saveUsefulLink($request, $usefulLink)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Useful link created');
            }

            return redirect()
                ->route('admin.useful-links.index')
                ->with('success', 'Useful link created');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('danger', 'Failed to create useful link');
    }

    public function edit(UsefulLink $usefulLink, Request $request): View
    {
        return view('admin.useful-links.edit', [
            'usefulLink' => $usefulLink,
            'usefulLinkCategories' => $this->usefulLinkCategories(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, UsefulLink $usefulLink): RedirectResponse
    {
        if ($this->saveUsefulLink($request, $usefulLink)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Useful link updated');
            }

            return redirect()
                ->route('admin.useful-links.index')
                ->with('success', 'Useful link updated');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('error', 'Failed to update useful link');
    }

    public function destroy(UsefulLink $usefulLink, Request $request): RedirectResponse
    {
        $usefulLink->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Useful link deleted');
        }

        return redirect()
            ->route('admin.useful-links.index')
            ->with('success', 'Useful link deleted');
    }

    private function saveUsefulLink(FormRequest $request, UsefulLink $usefulLink): bool
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $usefulLink->fill($data);
            $usefulLink->save();

            if ($request->hasFile('image')) {
                $usefulLink->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function usefulLinkCategories(): Collection
    {
        return UsefulLinkCategory::orderBy('name', 'asc')->get();
    }
}

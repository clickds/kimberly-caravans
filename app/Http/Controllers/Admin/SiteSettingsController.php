<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SiteSettings\StoreRequest;
use App\Http\Requests\Admin\SiteSettings\UpdateRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SiteSettingsController extends BaseController
{
    public function index(): View
    {
        return view('admin.site-settings.index', [
            'siteSettings' => SiteSetting::all()
        ]);
    }

    public function create(): View
    {
        return view('admin.site-settings.create', [
            'siteSettingNames' => SiteSetting::VALID_SETTINGS,
            'siteSetting' => new SiteSetting(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            SiteSetting::create($request->validated());

            DB::commit();

            return redirect()
                ->route('admin.site-settings.index')
                ->with('success', 'Site setting created');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create site setting');
        }
    }

    public function edit(SiteSetting $site_setting): View
    {
        return view('admin.site-settings.edit', [
            'siteSettingNames' => SiteSetting::VALID_SETTINGS,
            'siteSetting' => $site_setting,
        ]);
    }

    public function update(UpdateRequest $request, SiteSetting $site_setting): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $site_setting->update($request->validated());

            DB::commit();

            return redirect()
                ->route('admin.site-settings.index')
                ->with('success', 'Site setting updated');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update site setting');
        }
    }

    public function destroy(SiteSetting $site_setting): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $site_setting->delete();

            DB::commit();

            return redirect()
                ->route('admin.site-settings.index')
                ->with('success', 'Site setting deleted');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete site setting');
        }
    }
}

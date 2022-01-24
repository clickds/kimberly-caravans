<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Logos\StoreRequest;
use App\Http\Requests\Admin\Logos\UpdateRequest;
use App\Models\Logo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LogosController extends BaseController
{
    public function index(Request $request): View
    {
        return view('admin.logos.index', ['logos' => Logo::all()]);
    }

    public function create(): View
    {
        return view('admin.logos.create', [
            'logo' => new Logo(),
            'displayLocations' => Logo::VALID_DISPLAY_LOCATIONS,
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $logo = Logo::create($request->validated());
            $this->saveImage($request, $logo);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create logo');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Logo created');
        }

        return redirect()
            ->route('admin.logos.index')
            ->with('success', 'Logo created');
    }


    public function edit(Logo $logo): View
    {
        return view('admin.logos.edit', [
            'logo' => $logo,
            'displayLocations' => Logo::VALID_DISPLAY_LOCATIONS,
        ]);
    }


    public function update(UpdateRequest $request, Logo $logo): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $logo->update($request->validated());
            $this->saveImage($request, $logo);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update logo');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Logo updated');
        }

        return redirect()
            ->route('admin.logos.index')
            ->with('success', 'Logo updated');
    }

    public function destroy(Logo $logo, Request $request): RedirectResponse
    {
        $logo->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Logo deleted');
        }

        return redirect()
            ->route('admin.logos.index')
            ->with('success', 'Logo deleted');
    }

    private function saveImage(FormRequest $request, Logo $logo): void
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image') && isset($validatedData['image'])) {
            $logo->addMedia($validatedData['image'])->toMediaCollection('image');
        }
    }
}

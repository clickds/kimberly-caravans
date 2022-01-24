<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Ctas\StoreRequest;
use App\Http\Requests\Admin\Ctas\UpdateRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Cta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\ImageDeletable;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class CtasController extends BaseController
{
    use ImageDeletable;

    public function index(Request $request): View
    {
        $ctas = Cta::ransack($request->all())->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.ctas.index', [
            'ctas' => $ctas,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.ctas.create', [
            'cta' => new Cta(),
            'sites' => $this->fetchSites(),
            'types' => $this->fetchTypes(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $cta = new Cta();

        if ($this->saveCta($cta, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Call to action created');
            }

            return redirect()
                ->route('admin.ctas.index')
                ->with('success', 'Call to action created');
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to create call to action');
    }

    public function edit(Cta $cta, Request $request): View
    {
        return view('admin.ctas.edit', [
            'cta' => $cta,
            'sites' => $this->fetchSites(),
            'types' => $this->fetchTypes(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Cta $cta): RedirectResponse
    {
        if ($this->saveCta($cta, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Call to action updated');
            }

            return redirect()
                ->route('admin.ctas.index')
                ->with('success', 'Call to action updated');
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to update call to action');
    }

    public function destroy(Cta $cta, Request $request): RedirectResponse
    {
        $cta->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Call to action deleted');
        }

        return redirect()
            ->route('admin.ctas.index')
            ->with('success', 'Call to action deleted');
    }

    private function saveCta(Cta $cta, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $data = array_filter($request->validated());
            $cta->fill($data);
            $cta->save();
            $this->addImage($request, $cta);
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            return false;
        }
    }

    private function addImage(FormRequest $request, Cta $cta): void
    {
        if ($request->exists('image')) {
            $cta->addMediaFromRequest('image')->toMediaCollection('image');
        }
    }

    private function fetchTypes(): array
    {
        return Cta::TYPES;
    }
}

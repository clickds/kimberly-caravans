<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Layouts\StoreRequest;
use App\Http\Requests\Admin\Layouts\UpdateRequest;
use App\Models\Layout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayoutsController extends BaseController
{
    public function index(Request $request): View
    {
        $layouts = Layout::ransack($request->all())->orderBy('code', 'asc')->paginate();

        return view('admin.layouts.index', [
            'layouts' => $layouts,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.layouts.create', [
            'layout' => new Layout(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function edit(Layout $layout, Request $request): View
    {
        return view('admin.layouts.edit', [
            'layout' => $layout,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Layout::create($request->validated());

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Layout created');
        }

        return redirect()->route('admin.layouts.index')->with('success', 'Layout created');
    }

    public function update(Layout $layout, UpdateRequest $request): RedirectResponse
    {
        $layout->update($request->validated());

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Layout updated');
        }

        return redirect()->route('admin.layouts.index')->with('success', 'Layout updated');
    }
}

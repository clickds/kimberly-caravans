<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Testimonial;
use App\Http\Requests\Admin\Testimonials\StoreRequest;
use App\Http\Requests\Admin\Testimonials\UpdateRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TestimonialsController extends BaseController
{
    public function index(Request $request): View
    {
        $testimonials = Testimonial::ransack($request->all())
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_TESTIMONIALS_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        $testimonial = new Testimonial();

        return view('admin.testimonials.create', [
            'sites' => $this->fetchSites(),
            'testimonial' => $testimonial,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $testimonial = Testimonial::create($request->validated());
            $testimonial->sites()->sync($request->get('site_ids'));
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return back()
                ->withInput()
                ->with('warning', 'Failed to create testimonial');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Testimonial created');
        }

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created');
    }

    public function edit(Testimonial $testimonial, Request $request): View
    {
        return view('admin.testimonials.edit', [
            'sites' => $this->fetchSites(),
            'testimonial' => $testimonial,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Testimonial $testimonial): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $testimonial->update($request->validated());
            $testimonial->sites()->sync($request->get('site_ids'));
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return back()
                ->with('warning', 'Failed to update testimonial');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Testimonial updated');
        }

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated');
    }

    public function destroy(Testimonial $testimonial, Request $request): RedirectResponse
    {
        $testimonial->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Testimonial deleted');
        }

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted');
    }
}

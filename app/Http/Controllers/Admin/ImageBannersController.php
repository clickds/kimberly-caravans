<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ImageBanners\StoreRequest;
use App\Http\Requests\Admin\ImageBanners\UpdateRequest;
use App\Models\ImageBanner;
use App\Services\ImageBanner\Saver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImageBannersController extends BaseController
{
    public function index(Request $request): View
    {
        $imageBanners = ImageBanner::with('pages:id,slug,name', 'pages.parent:id,slug')
            ->withCount('buttons')
            ->ransack($request->all());

        $imageBanners = $imageBanners->orderable($request->input('sort_by', 'updated_at_desc'))
            ->paginate(15);

        return view('admin.image-banners.index', [
            'imageBanners' => $imageBanners,
        ]);
    }

    public function create(Request $request): View
    {
        $imageBanner = new ImageBanner();

        return view('admin.image-banners.create', [
            'imageBanner' => $imageBanner,
            'textColours' => $this->textColours(),
            'backgroundColours' => $this->backgroundColours(),
            'icons' => $this->icons(),
            'textAlignments' => $this->fetchTextAlignments(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $imageBanner = new ImageBanner();

        if ($this->saveImageBanner($request, $imageBanner)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Image banner created');
            }

            return redirect()
                ->route('admin.image-banners.index')
                ->with('success', 'Image banner created');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('warning', 'Failed to save image banner');
    }


    public function edit(ImageBanner $imageBanner, Request $request): View
    {
        return view('admin.image-banners.edit', [
            'imageBanner' => $imageBanner,
            'textColours' => $this->textColours(),
            'backgroundColours' => $this->backgroundColours(),
            'icons' => $this->icons(),
            'textAlignments' => $this->fetchTextAlignments(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }


    public function update(UpdateRequest $request, ImageBanner $imageBanner): RedirectResponse
    {
        if ($this->saveImageBanner($request, $imageBanner)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Image banner updated');
            }

            return redirect()
                ->route('admin.image-banners.index')
                ->with('success', 'Image banner updated');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('warning', 'Failed to update image banner');
    }

    public function destroy(ImageBanner $imageBanner, Request $request): RedirectResponse
    {
        $imageBanner->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Image banner deleted');
        }

        return redirect()
            ->route('admin.image-banners.index')
            ->with('success', 'Image banner deleted');
    }

    private function saveImageBanner(FormRequest $request, ImageBanner $imageBanner): bool
    {
        $saver = new Saver($request, $imageBanner);
        return $saver->call();
    }

    private function textColours(): array
    {
        return ImageBanner::TEXT_COLOURS;
    }

    private function fetchTextAlignments(): array
    {
        return ImageBanner::TEXT_ALIGNMENTS;
    }

    private function backgroundColours(): array
    {
        return ImageBanner::BACKGROUND_COLOURS;
    }

    private function icons(): array
    {
        return ImageBanner::ICONS;
    }
}

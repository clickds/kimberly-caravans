<?php

namespace App\Http\Controllers\Admin\MotorhomeRange;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\VehicleRange\GalleryImages\StoreRequest;
use App\Models\MotorhomeRange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class ExteriorGalleryImagesController extends Controller
{
    public function index(MotorhomeRange $motorhomeRange): View
    {
        $galleryImages = $motorhomeRange->getMedia('exteriorGallery');

        return view('admin.motorhome-range.exterior-gallery-images.index', [
            'motorhomeRange' => $motorhomeRange,
            'galleryImages' => $galleryImages,
        ]);
    }

    public function create(MotorhomeRange $motorhomeRange): View
    {
        return view('admin.motorhome-range.exterior-gallery-images.create', [
            'motorhomeRange' => $motorhomeRange,
        ]);
    }

    public function store(StoreRequest $request, MotorhomeRange $motorhomeRange): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $image = $motorhomeRange->addMediaFromRequest('image')
                ->usingName($request->get('name'))
                ->toMediaCollection('exteriorGallery');

            if ($position = $request->get('position')) {
                $image->order_column = $position;
                $image->save();
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
        }

        return redirect()->route('admin.motorhome-ranges.exterior-gallery-images.index', $motorhomeRange);
    }

    public function destroy(MotorhomeRange $motorhomeRange, Media $exterior_gallery_image): RedirectResponse
    {
        $exterior_gallery_image->delete();

        return redirect()->route('admin.motorhome-ranges.exterior-gallery-images.index', $motorhomeRange);
    }
}

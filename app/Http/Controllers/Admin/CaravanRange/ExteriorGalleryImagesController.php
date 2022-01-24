<?php

namespace App\Http\Controllers\Admin\CaravanRange;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\VehicleRange\GalleryImages\StoreRequest;
use App\Models\CaravanRange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class ExteriorGalleryImagesController extends Controller
{
    public function index(CaravanRange $caravanRange): View
    {
        $galleryImages = $caravanRange->getMedia('exteriorGallery');

        return view('admin.caravan-range.exterior-gallery-images.index', [
            'caravanRange' => $caravanRange,
            'galleryImages' => $galleryImages,
        ]);
    }

    public function create(CaravanRange $caravanRange): View
    {
        return view('admin.caravan-range.exterior-gallery-images.create', [
            'caravanRange' => $caravanRange,
        ]);
    }

    public function store(StoreRequest $request, CaravanRange $caravanRange): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $image = $caravanRange->addMediaFromRequest('image')
                ->usingName($request->get('name'))
                ->toMediaCollection('exteriorGallery');

            if ($position = $request->get('position')) {
                $image->order_column = $position;
                $image->save();
            }
            DB::commit();
        } catch (Throwable $e) {
            var_dump($e);
            DB::rollBack();
            Log::error($e);
        }

        return redirect()->route('admin.caravan-ranges.exterior-gallery-images.index', $caravanRange);
    }

    public function destroy(CaravanRange $caravanRange, Media $exterior_gallery_image): RedirectResponse
    {
        $exterior_gallery_image->delete();

        return redirect()->route('admin.caravan-ranges.exterior-gallery-images.index', $caravanRange);
    }
}

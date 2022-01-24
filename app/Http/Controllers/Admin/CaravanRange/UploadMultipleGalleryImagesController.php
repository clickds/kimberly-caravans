<?php

namespace App\Http\Controllers\Admin\CaravanRange;

use App\Http\Controllers\Controller;
use App\Models\CaravanRange;
use Illuminate\View\View;

class UploadMultipleGalleryImagesController extends Controller
{
    public function create(CaravanRange $caravanRange, string $galleryType): View
    {
        $storeUrl = $this->storeUrl($caravanRange, $galleryType);

        return view('admin.caravan-range.upload-multiple-gallery-images.create', [
            'storeUrl' => $storeUrl,
        ]);
    }

    private function storeUrl(CaravanRange $caravanRange, string $galleryType): string
    {
        return route('api.admin.caravan-ranges.gallery.upload-multiple.store', [
            'caravanRange' => $caravanRange,
            'galleryType' => $galleryType,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin\MotorhomeRange;

use App\Http\Controllers\Controller;
use App\Models\MotorhomeRange;
use Illuminate\View\View;

class UploadMultipleGalleryImagesController extends Controller
{
    public function create(MotorhomeRange $motorhomeRange, string $galleryType): View
    {
        $storeUrl = $this->storeUrl($motorhomeRange, $galleryType);

        return view('admin.motorhome-range.upload-multiple-gallery-images.create', [
            'storeUrl' => $storeUrl,
        ]);
    }
    private function storeUrl(MotorhomeRange $motorhomeRange, string $galleryType): string
    {
        return route('api.admin.motorhome-ranges.gallery.upload-multiple.store', [
            'motorhomeRange' => $motorhomeRange,
            'galleryType' => $galleryType,
        ]);
    }
}

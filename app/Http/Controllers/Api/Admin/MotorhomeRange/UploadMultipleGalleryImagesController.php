<?php

namespace App\Http\Controllers\Api\Admin\MotorhomeRange;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\VehicleRange\GalleryImages\MultipleUploadStoreRequest;
use App\Models\MotorhomeRange;
use Illuminate\Http\JsonResponse;

class UploadMultipleGalleryImagesController extends Controller
{
    public function store(
        MultipleUploadStoreRequest $request,
        MotorhomeRange $motorhomeRange,
        string $galleryType
    ): JsonResponse {
        if ($request->hasFile('file')) {
            $collectionName = $this->collectionName($galleryType);
            $file = $request->file('file');
            $name = str_replace('_', ' ', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $name = str_replace('-', ' ', $name);

            $motorhomeRange->addMedia($file)
                ->usingName($name)
                ->toMediaCollection($collectionName);
        }

        return new JsonResponse(null, 201);
    }

    private function collectionName(string $galleryType): string
    {
        switch ($galleryType) {
            case 'interior-gallery-images':
                return 'interiorGallery';
            case 'exterior-gallery-images':
                return 'exteriorGallery';
            default:
                return 'featureGallery';
        }
    }
}

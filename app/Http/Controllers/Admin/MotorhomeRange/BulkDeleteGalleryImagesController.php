<?php

namespace App\Http\Controllers\Admin\MotorhomeRange;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VehicleRange\GalleryImages\BulkDeleteRequest;
use App\Models\MotorhomeRange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class BulkDeleteGalleryImagesController extends Controller
{
    public function __invoke(
        BulkDeleteRequest $request,
        MotorhomeRange $motorhomeRange,
        string $galleryType
    ): RedirectResponse {
        try {
            $galleryImageIds = $request->validated()['gallery_image_ids'];

            $imagesToDelete = $motorhomeRange->galleryImages()
                ->where('collection_name', $galleryType)
                ->whereIn('id', $galleryImageIds)
                ->get();

            $failedToDelete = $imagesToDelete->filter(function (Media $media) {
                try {
                    return !$media->delete();
                } catch (Throwable $e) {
                    Log::error($e);
                    return true;
                }
            });

            if ($failedToDelete->isEmpty()) {
                return redirect()->back()->with('success', 'Successfully deleted gallery images');
            } else {
                return redirect()->back()->with('warning', 'There was an issue when deleting some gallery images');
            }
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'Failed to delete gallery images');
        }
    }
}

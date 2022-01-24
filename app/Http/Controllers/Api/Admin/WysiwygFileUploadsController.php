<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\WysiwygFileUploads\StoreRequest;
use App\Models\WysiwygUpload;
use App\Services\Wysiwyg\ResponsiveImageUrlsGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class WysiwygFileUploadsController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        $file = $request->file('upload');
        if (is_null($file)) {
            return new JsonResponse([
                'error' => [
                    'message' => 'no file attached',
                ],
            ], 500);
        }
        if (is_array($file)) {
            $file = $file[0];
        }
        $name = $file->getClientOriginalName();
        DB::beginTransaction();
        try {
            $wysiwygUpload = WysiwygUpload::create([
                'name' => $name,
            ]);
            $media = $wysiwygUpload->addMedia($file)->toMediaCollection('file');

            DB::commit();
            return new JsonResponse([
                'urls' => $this->responsiveImageUrls($media),
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return new JsonResponse([
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    private function responsiveImageUrls(Media $media): array
    {
        $generator = new ResponsiveImageUrlsGenerator($media);
        return $generator->call();
    }
}

<?php

namespace App\Services\ImageBanner;

use App\Models\ImageBanner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Saver
{
    private FormRequest $request;
    private ImageBanner $imageBanner;

    public function __construct(FormRequest $request, ImageBanner $imageBanner)
    {
        $this->request = $request;
        $this->imageBanner = $imageBanner;
    }

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $this->updateImageBanner();
            $this->updateImage();

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function updateImageBanner(): void
    {
        $imageBanner = $this->getImageBanner();
        $data = $this->validatedData();
        $imageBanner->fill($data);
        $imageBanner->save();
    }

    private function updateImage(): void
    {
        $request = $this->getRequest();
        $imageAlt = $request->input('image_alt');

        if ($request->hasFile('image')) {
            $this->addNewImage($imageAlt);
        }
        $this->updateImageAlt($imageAlt);
    }

    private function addNewImage(?string $imageAlt): void
    {
        $imageBanner = $this->getImageBanner();
        $fileAdder = $imageBanner->addMediaFromRequest('image');
        if ($imageAlt) {
            $fileAdder->usingName($imageAlt);
        }
        $fileAdder->toMediaCollection('image');
    }

    private function updateImageAlt(?string $imageAlt): void
    {
        if (is_null($imageAlt)) {
            return;
        }
        $media = $this->getImageBanner()->getFirstMedia('image');
        if (is_null($media)) {
            return;
        }
        $media->name = $imageAlt;
        $media->save();
    }

    private function validatedData(): array
    {
        return $this->getRequest()->validated();
    }

    private function getImageBanner(): ImageBanner
    {
        return $this->imageBanner;
    }

    private function getRequest(): FormRequest
    {
        return $this->request;
    }
}

<?php

namespace App\Services\Importers\StockFeed;

use App\Models\Dealer;
use App\Models\Interfaces\StockItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Layout;
use App\Models\Manufacturer;
use App\Services\Importers\StockFeed\Exceptions\NoLayoutException;
use Exception;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class BaseCreator
{
    public const EXTENSIONS = [
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public const COLLECTION_LAYOUT = 'layout';
    public const COLLECTION_IMAGES = 'images';
    public const COLLECTION_NAMES = [
        self::COLLECTION_LAYOUT,
        self::COLLECTION_IMAGES,
    ];

    /**
     * @var array
     */
    protected $feedItemData;
    /**
     * @var \App\Services\Importers\StockFeed\Fetcher
     */
    protected $fetcher;

    public function __construct(array $feedItemData, Fetcher $fetcher)
    {
        $this->feedItemData = $feedItemData;
        $this->fetcher = $fetcher;
    }

    protected function fetchModel(): string
    {
        $value = $this->fetchValueFromFeedItemData("Model");

        if (is_null($value)) {
            throw new Exception("No model provided by feed");
        }

        return $value;
    }

    /**
     * @param \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem $stockItem
     */
    protected function createFeatures($stockItem): void
    {
        $features = $this->fetchValueFromFeedItemData("VehicleFeatures", []);
        foreach ($features as $feature_name) {
            $stockItem->features()->firstOrCreate([
                'name' => Str::title($feature_name),
            ]);
        }
    }

    /**
     * @param \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem $stockItem
     */
    protected function createImages($stockItem): void
    {
        $imageReferences = $this->fetchValueFromFeedItemData("Images", []);

        $this->deleteImagesNoLongerUsed($stockItem, $imageReferences);
        $this->reorderImages($stockItem, $imageReferences);

        foreach ($imageReferences as $index => $imageReference) {
            $collectionName = $this->calculateImageCollectionName($index);
            $this->importImage($stockItem, $imageReference, $collectionName, $index);
        }
    }

    protected function calculateImageCollectionName(int $index): string
    {
        switch ($index) {
            case 11:
                return static::COLLECTION_LAYOUT;
            default:
                return static::COLLECTION_IMAGES;
        }
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    protected function fetchValueFromFeedItemData(string $key, $default = null)
    {
        $value = Arr::get($this->getFeedItemData(), $key, $default);
        // Saxon feed seems to return empty strings rather than sensible stuff
        if (empty($value)) {
            return $default;
        }
        return $value;
    }

    protected function getFeedItemData(): array
    {
        return $this->feedItemData;
    }

    protected function getFetcher(): Fetcher
    {
        return $this->fetcher;
    }

    protected function fetchImage(string $imageId): array
    {
        return $this->getFetcher()->getImage($imageId);
    }

    /**
     * @param \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem $stockItem
     */
    protected function deleteImagesNoLongerUsed($stockItem, array $imageReferences): void
    {
        if (empty($imageReferences)) {
            return;
        }

        $stockItem->media()
            ->whereIn('collection_name', static::COLLECTION_NAMES)
            ->whereNotIn('name', $imageReferences)
            ->delete();
    }

    /**
     * @param \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem $stockItem
     */
    protected function reorderImages($stockItem, array $imageReferences): void
    {
        if (empty($imageReferences)) {
            return;
        }

        $media = $stockItem->media()
            ->whereIn('collection_name', static::COLLECTION_NAMES)
            ->whereIn('name', $imageReferences)
            ->get();

        $media->map(function (Media $media) use ($imageReferences) {
            $order = array_search($media->name, $imageReferences);

            if ($media->order_column !== $order) {
                $media->update(['order_column' => $order]);
            }
        });
    }

    /**
     * @param \App\Models\CaravanStockItem|\App\Models\MotorhomeStockItem $stockItem
     */
    protected function importImage($stockItem, string $imageReference, string $collectionName, int $index): void
    {
        $mediaExists = $stockItem->media()
            ->where('collection_name', $collectionName)
            ->where('name', $imageReference)
            ->exists();

        if ($mediaExists) {
            return;
        }

        ['mime_type' => $mimeType, 'data' => $base64ImageString] = $this->fetchImage($imageReference);

        $filename = $this->calculateFilename($imageReference, $mimeType);

        $stockItem->addMediaFromBase64($base64ImageString)
            ->usingName($imageReference)
            ->usingFilename($filename)
            ->withAttributes([
                'order_column' => $index,
            ])
            ->toMediaCollection($collectionName);
    }

    protected function findDealer(): ?Dealer
    {
        $feedLocationCode = $this->fetchValueFromFeedItemData("Location");

        if (!$feedLocationCode) {
            return null;
        }

        return Dealer::feedLocationCode($feedLocationCode)->first();
    }

    /**
     * Retrive Layout by code, or create it with the name
     */
    protected function findOrCreateLayout(): Layout
    {
        $layoutCode = $this->fetchValueFromFeedItemData("LayoutCode");
        $layoutName = $this->fetchValueFromFeedItemData("LayoutType");
        if (is_null($layoutCode) || is_null($layoutName)) {
            throw new NoLayoutException('No layout data provided');
        }
        return Layout::firstOrCreate(
            ['code' => $layoutCode],
            ['name' => $layoutName]
        );
    }

    /**
     * Retrive Manufacturer by name, or create it
     */
    protected function findOrCreateManufacturer(): Manufacturer
    {
        $make = $this->fetchValueFromFeedItemData("Make");
        if (is_null($make)) {
            throw new Exception('Feed provided no make');
        }

        return Manufacturer::firstOrCreate(['name' => $this->manipulateManufacturerName($make)]);
    }

    protected function createFeedStockItemVideo(StockItem $stockItem, string $videoUrl): void
    {
        if (empty($videoUrl)) {
            $stockItem->feedStockItemVideo()->delete();
            return;
        }
        $video = $stockItem->feedStockItemVideo ?: $stockItem->feedStockItemVideo()->make();
        $video->youtube_url = $videoUrl;
        $video->save();
        $youtubeId = $this->fetchYoutubeId($videoUrl);
        if ($youtubeId) {
            $imageUrl = 'https://img.youtube.com/vi/' . $youtubeId . '/default.jpg';
            $video->addMediaFromUrl($imageUrl)->toMediaCollection('image');
        }
    }

    private function calculateFilename(string $imageReference, string $mimeType): string
    {
        $parts = array_filter([$imageReference, $this->imageExtension($mimeType)]);
        return implode('.', $parts);
    }

    private function imageExtension(string $mimeType): string
    {
        return Arr::get(self::EXTENSIONS, $mimeType);
    }

    private function fetchYoutubeId(string $videoUrl): ?string
    {
        $queryString = parse_url($videoUrl, PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $queryVars);
            return $queryVars['v'];
        }
        return null;
    }

    private function manipulateManufacturerName(string $name): string
    {
        $name = str_replace('AUTO SLEEPERS', 'AUTO-SLEEPERS', $name);
        $name = str_replace('AUTO TRAIL', 'AUTO-TRAIL', $name);
        $name = str_replace('AUTOTRAIL', 'AUTO-TRAIL', $name);
        return $name;
    }
}

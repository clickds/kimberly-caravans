<?php

namespace App\OldSite\Importers;

use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\MotorhomeRange;
use App\OldSite\Models\Video as OldSiteVideo;
use App\Models\Video as NewSiteVideo;
use App\Models\Page;
use App\Models\VideoCategory;
use App\Services\Pageable\VideoPageSaver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class VideoImporter extends BaseImporter
{
    private Collection $categories;

    public const CATEGORY_NAMES = [
        'Motorhomes',
        'Caravans',
        'Aftercare videos',
    ];

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $this->removeExistingVideos();
            $this->import();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function import(): void
    {
        $categories = collect();
        foreach (self::CATEGORY_NAMES as $categoryName) {
            $category = VideoCategory::firstOrCreate([
                'name' => $categoryName,
            ]);
            $categories->push($category);
        }
        $this->categories = $categories;
        OldSiteVideo::with([
            'panels' => function ($query) {
                return $query->importable()->orderBy('sort_order', 'asc');
            }
        ])->chunk(200, function ($oldSiteVideos) {
            foreach ($oldSiteVideos as $oldSiteVideo) {
                $this->importVideo($oldSiteVideo);
            }
        });
    }

    private function removeExistingVideos(): void
    {
        // Done this way so that associated objects like media and pages get deleted
        foreach (NewSiteVideo::cursor() as $video) {
            $video->delete();
        };
    }

    private function importVideo(OldSiteVideo $oldSiteVideo): void
    {
        $this->outputInfo('Importing old video: ' . $oldSiteVideo->id);
        $newVideo = $this->createVideo($oldSiteVideo);
        $this->outputInfo('New video: ' . $newVideo->id);
        $newVideo->sites()->attach($this->getDefaultSite());
        $this->attachImage($newVideo, $oldSiteVideo);
        $this->attachCaravanRanges($newVideo, $oldSiteVideo);
        $this->attachMotorhomeRanges($newVideo, $oldSiteVideo);
        $this->attachCategories($newVideo, $oldSiteVideo);
        $page = $this->createPage($newVideo);
        if (is_null($page)) {
            return;
        }
        $this->importPanels($page, $oldSiteVideo->panels);
    }

    private function attachCaravanRanges(NewSiteVideo $newSiteVideo, OldSiteVideo $oldSiteVideo): void
    {
        $oldCategoryNames = $oldSiteVideo->mediaCategories()->pluck('name');
        $caravanRanges = CaravanRange::whereIn('name', $oldCategoryNames)->pluck('id');
        $newSiteVideo->caravanRanges()->attach($caravanRanges);
    }

    private function attachMotorhomeRanges(NewSiteVideo $newSiteVideo, OldSiteVideo $oldSiteVideo): void
    {
        $oldCategoryNames = $oldSiteVideo->mediaCategories()->pluck('name');
        $motorhomeRanges = MotorhomeRange::whereIn('name', $oldCategoryNames)->pluck('id');
        $newSiteVideo->motorhomeRanges()->attach($motorhomeRanges);
    }

    public function attachCategories(NewSiteVideo $newSiteVideo, OldSiteVideo $oldSiteVideo): void
    {
        $categories = $oldSiteVideo->mediaCategories()->pluck('name');
        $categoryIds = VideoCategory::whereIn('name', $categories)->pluck('id');
        $newSiteVideo->videoCategories()->attach($categoryIds);
    }

    private function createPage(NewSiteVideo $newSiteVideo): ?Page
    {
        $site = $this->getDefaultSite();
        if (is_null($site)) {
            return null;
        }
        $saver = new VideoPageSaver($newSiteVideo, $site);
        $saver->call();
        return $newSiteVideo->pages()->first();
    }

    private function attachImage(NewSiteVideo $newSiteVideo, OldSiteVideo $oldSiteVideo): void
    {
        $imageFileName = $oldSiteVideo->image_file_name;
        if (empty($imageFileName)) {
            return;
        }
        $imageUrl = $this->calculateAttachmentUrl(
            'Video',
            'image',
            $oldSiteVideo->id,
            $imageFileName
        );
        $newSiteVideo->addMediaFromUrl($imageUrl)->usingFileName($imageFileName)
            ->toMediaCollection('image');
    }

    private function createVideo(OldSiteVideo $oldSiteVideo): NewSiteVideo
    {
        $categories = $oldSiteVideo->mediaCategories()->pluck('name');
        $dealerIds = Dealer::whereIn('name', $categories)->pluck('id');
        $dealerId = $dealerIds->first();

        return NewSiteVideo::forceCreate([
            'dealer_id' => $dealerId,
            'embed_code' => strip_tags($oldSiteVideo->embed_markup, '<iframe>'),
            'title' => $oldSiteVideo->name,
            'excerpt' => $oldSiteVideo->teaser,
            'published_at' => $oldSiteVideo->date_live,
            'created_at' => $oldSiteVideo->created_at,
            'updated_at' => $oldSiteVideo->updated_at,
            'type' => 'Both',
        ]);
    }
}

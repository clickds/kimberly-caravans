<?php

namespace App\Services\Pageable;

use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Video;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Facades\Log;

class VideoPageSaver
{
    /**
     * @var \App\Models\Site
     */
    private $site;
    /**
     * @var \App\Models\Video
     */
    private $video;

    public function __construct(Video $video, Site $site)
    {
        $this->video = $video;
        $this->site = $site;
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();
            $this->saveVideoPage();
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
        }
    }

    private function saveVideoPage(): void
    {
        $page = $this->findOrInitializeVideoPage();
        $page->name = $this->getVideo()->title;
        $page->meta_title = $this->getVideo()->title;
        $page->parent_id = $this->findOrCreateVideoListingsPage()->id;
        $page->save();
    }

    private function findOrInitializeVideoPage(): Page
    {
        return $this->getVideo()->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => Page::TEMPLATE_VIDEO_SHOW,
        ]);
    }

    private function findOrCreateVideoListingsPage(): Page
    {
        return Page::firstOrCreate(
            [
                'site_id' => $this->getSite()->id,
                'template' => Page::TEMPLATE_VIDEOS_LISTING,
            ],
            [
                'name' => 'Videos',
                'meta_title' => 'Videos',
            ]
        );
    }

    private function getVideo(): Video
    {
        return $this->video;
    }

    private function getSite(): Site
    {
        return $this->site;
    }
}

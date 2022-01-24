<?php

namespace App\OldSite\Importers;

use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\MotorhomeRange;
use App\OldSite\Models\Review as OldSiteReview;
use App\Models\Review as NewSiteReview;
use App\Models\ReviewCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReviewImporter extends BaseImporter
{
    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $this->removeExistingReviews();
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
        foreach (['Motorhomes', 'Caravans'] as $category) {
            ReviewCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
        OldSiteReview::chunk(200, function ($oldSiteReviews) {
            foreach ($oldSiteReviews as $oldSiteReview) {
                $this->importReview($oldSiteReview);
            }
        });
    }

    private function removeExistingReviews(): void
    {
        // Done this way so that associated objects like media and pages get deleted
        foreach (NewSiteReview::cursor() as $video) {
            $video->delete();
        };
    }

    private function importReview(OldSiteReview $oldSiteReview): void
    {
        $this->outputInfo('Importing old review: ' . $oldSiteReview->id);
        $newReview = $this->createReview($oldSiteReview);
        $this->outputInfo('New review: ' . $newReview->id);
        $this->attachImage($newReview, $oldSiteReview);
        $this->attachCaravanRanges($newReview, $oldSiteReview);
        $this->attachMotorhomeRanges($newReview, $oldSiteReview);
        $this->attachReviewFile($newReview, $oldSiteReview);
    }

    private function attachCaravanRanges(NewSiteReview $newSiteReview, OldSiteReview $oldSiteReview): void
    {
        $oldCategoryNames = $oldSiteReview->mediaCategories()->pluck('name');
        $caravanRanges = CaravanRange::whereIn('name', $oldCategoryNames)->pluck('id');
        $newSiteReview->caravanRanges()->attach($caravanRanges);
    }

    private function attachMotorhomeRanges(NewSiteReview $newSiteReview, OldSiteReview $oldSiteReview): void
    {
        $oldCategoryNames = $oldSiteReview->mediaCategories()->pluck('name');
        $motorhomeRanges = MotorhomeRange::whereIn('name', $oldCategoryNames)->pluck('id');
        $newSiteReview->motorhomeRanges()->attach($motorhomeRanges);
    }

    private function attachImage(NewSiteReview $newSiteReview, OldSiteReview $oldSiteReview): void
    {
        $imageFileName = $oldSiteReview->image_file_name;
        if (empty($imageFileName)) {
            return;
        }
        $imageUrl = $this->calculateAttachmentUrl(
            'Review',
            'image',
            $oldSiteReview->id,
            $imageFileName
        );
        $newSiteReview->addMediaFromUrl($imageUrl)
            ->usingFileName($imageFileName)->toMediaCollection('image');
    }

    private function attachReviewFile(NewSiteReview $newSiteReview, OldSiteReview $oldSiteReview): void
    {
        $pdfFileName = $oldSiteReview->pdf_file_name;
        if (empty($pdfFileName)) {
            return;
        }
        $pdfUrl = $this->calculateAttachmentUrl(
            'Review',
            'pdf',
            $oldSiteReview->id,
            $pdfFileName
        );
        $newSiteReview->addMediaFromUrl($pdfUrl)
            ->usingFileName($pdfFileName)->toMediaCollection('review_file');
    }

    private function createReview(OldSiteReview $oldSiteReview): NewSiteReview
    {
        $site = $this->getDefaultSite();
        $siteId = $site ? $site->id : null;
        $categories = $oldSiteReview->mediaCategories()->pluck('name');
        $dealerIds = Dealer::whereIn('name', $categories)->pluck('id');
        $dealerId = $dealerIds->first();
        $categoryIds = ReviewCategory::whereIn('name', $categories)->pluck('id');
        $categoryId = $categoryIds->first();

        return NewSiteReview::forceCreate([
            'review_category_id' => $categoryId,
            'dealer_id' => $dealerId,
            'site_id' => $siteId,
            'date' => $oldSiteReview->date_live,
            'published_at' => $oldSiteReview->date_live,
            'expired_at' => $oldSiteReview->date_expires,
            'created_at' => $oldSiteReview->created_at,
            'updated_at' => $oldSiteReview->updated_at,
            'link' => $oldSiteReview->source_url,
            'magazine' => $oldSiteReview->source,
            'position' => 0,
            'title' => $oldSiteReview->name,
            'text' => $oldSiteReview->teaser,
        ]);
    }
}

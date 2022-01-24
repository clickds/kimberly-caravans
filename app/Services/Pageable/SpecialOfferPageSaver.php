<?php

namespace App\Services\Pageable;

use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\SpecialOffer;
use App\Models\Page;
use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Note currently a special offer only has a single page
 */
class SpecialOfferPageSaver
{
    /**
     * @var \App\Models\Site
     */
    private $site;
    /**
     * @var \App\Models\SpecialOffer
     */
    private $specialOffer;

    public function __construct(SpecialOffer $specialOffer, Site $site)
    {
        $this->specialOffer = $specialOffer;
        $this->site = $site;
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();
            $this->deleteUnneededPages();
            $this->saveSpecialOfferPages();
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
        }
    }

    private function deleteUnneededPages(): void
    {
        $unneededTemplates = $this->pageTemplatesNeeded();
        $this->getSpecialOffer()->pages()->whereNotIn('template', $unneededTemplates)->delete();
    }

    private function saveSpecialOfferPages(): void
    {
        $templates = $this->pageTemplatesNeeded();
        foreach ($templates as $template) {
            $page = $this->findOrInitializeSpecialOfferPage($template);
            $page->parent_id = $this->findIdOfSpecialOfferListingPageId();
            $page->name = $this->getSpecialOffer()->name;
            $page->meta_title = $this->getSpecialOffer()->name;
            $page->save();
        }
    }

    private function pageTemplatesNeeded(): array
    {
        switch ($this->getSpecialOffer()->type) {
            case SpecialOffer::TYPE_CARAVAN:
                return [
                    Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
                ];
            case SpecialOffer::TYPE_MOTORHOME:
                return [
                    Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
                ];
            default:
                return [
                    Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
                    Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
                ];
        }
    }

    private function findOrInitializeSpecialOfferPage(string $template): Page
    {
        return $this->getSpecialOffer()->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => $template,
        ]);
    }

    private function findIdOfSpecialOfferListingPageId(): ?int
    {
        $listingPage = Page::where('site_id', $this->getSite()->id)
            ->where('template', Page::TEMPLATE_SPECIAL_OFFERS_LISTING)
            ->first();

        if ($listingPage) {
            return $listingPage->id;
        }
        return null;
    }

    private function getSpecialOffer(): SpecialOffer
    {
        return $this->specialOffer;
    }

    private function getSite(): Site
    {
        return $this->site;
    }
}

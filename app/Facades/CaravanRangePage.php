<?php

namespace App\Facades;

use App\Models\Caravan;
use Illuminate\Http\Request;
use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\Form;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\PopUp;
use App\Models\Review;
use App\Models\Site;
use App\Models\SpecialOffer;
use App\Models\Video;
use App\Presenters\PanelType\VideoPresenter;
use App\Presenters\ReviewPresenter;
use App\Presenters\SitePresenter;
use App\Presenters\CaravanPresenter;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CaravanRangePage extends BasePage
{
    private CaravanRange $range;
    private Collection $caravans;
    private Collection $otherRangesByManufacturer;
    private Collection $rangeDealers;
    private array $dealers;
    private Collection $galleryImages;
    private Collection $reviews;
    private Collection $videos;
    private Collection $brochures;
    private ?Form $newsletterSignUpForm;
    private Collection $specialOffers;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->range = $page->pageable;
        $this->caravans = $this->range->caravans()->live()->orderBy('position', 'asc')->get();
        $this->otherRangesByManufacturer = $this->fetchOtherRangesByManufacturer(
            $this->range->manufacturer,
            $this->getSite()
        );
        $this->rangeDealers = $this->fetchRangeDealers();
        $this->dealers = $this->fetchDealers();
        $this->videos = $this->fetchVideos();
        $this->reviews = $this->fetchReviews();
        $this->brochures = $this->fetchBrochures();
        $this->newsletterSignUpForm = $this->fetchNewsletterSignUpForm();
        $this->specialOffers = $this->fetchSpecialOffers($this->range);
    }

    public function tabNames(): array
    {
        $tabs = ['models', 'features', 'technical', 'gallery'];

        if ($this->getSite()->show_buy_tab_on_new_model_pages) {
            $tabs[] = 'buy';
        }

        if ($this->getVideos()->isNotEmpty() || $this->getReviews()->isNotEmpty()) {
            $tabs[] = 'videos and reviews';
        }

        if ($this->getBrochures()->isNotEmpty()) {
            $tabs[] = 'brochures';
        }

        if ($this->getSite()->show_offers_tab_on_new_model_pages) {
            $tabs[] = 'offers';
        }

        if ($this->getSite()->show_dealer_ranges && $this->getRangeDealers()->isNotEmpty()) {
            $tabs[] = 'dealers';
        }

        return $tabs;
    }

    public function getHeaderImage(): ?Media
    {
        return $this->getRange()->getFirstMedia('mainImage');
    }

    public function getRangeDealers(): Collection
    {
        return $this->rangeDealers;
    }

    public function getDealers(): array
    {
        return $this->dealers;
    }

    public function getCaravans(): Collection
    {
        return $this->caravans;
    }

    public function getFormattedCaravansForVue(): Collection
    {
        return $this->getCaravans()->map(function (Caravan $caravan) {
            $sitePresenter = (new SitePresenter())->setWrappedObject($this->getSite());
            $presenter = (new CaravanPresenter())->setWrappedObject($caravan);
            $dayFloorplan = $presenter->dayFloorplan();
            $nightFloorplan = $presenter->nightFloorplan();

            $caravan->formattedName = $presenter->formattedName();
            $caravan->berthString = $presenter->berthString();
            $caravan->dayFloorplanImageUrl = $dayFloorplan ? $dayFloorplan->getUrl() : null;
            $caravan->nightFloorplanImageUrl = $nightFloorplan ? $nightFloorplan->getUrl() : null;
            $caravan->stockFormDetails = $presenter->stockFormDetails();
            $caravan->formattedLength = $presenter->formattedLength();
            $caravan->formattedWidth = $presenter->formattedWidth();
            $caravan->formattedHeight = $presenter->formattedHeight();
            $caravan->formattedMtplm = $presenter->formattedMtplm();
            $caravan->formattedMro = $presenter->formattedMro();
            $caravan->formattedPayload = $presenter->formattedPayload();
            $caravan->bedSizes = $presenter->bedSizes();
            $caravan->hasReducedPrice = $presenter->hasReducedPrice($sitePresenter);
            $caravan->formattedRecommendedPrice = $presenter->formattedRecommendedPrice($sitePresenter);
            $caravan->formattedPrice = $presenter->formattedPrice($sitePresenter);
            $caravan->formattedSaving = $presenter->formattedSaving($sitePresenter);
            $caravan->price = $presenter->price($sitePresenter);
            $caravan->stockItem = $presenter->stockItem;

            return $caravan;
        });
    }

    public function getFeatures(): Collection
    {
        return $this->getRange()
            ->features()
            ->forSite($this->getSite())
            ->orderBy('position', 'asc')
            ->get();
    }

    public function getSpecificationSmallPrints(): Collection
    {
        return $this->getRange()
            ->specificationSmallPrints()
            ->where('site_id', $this->getSite()->id)
            ->get();
    }

    public function getOtherRangesByManufacturer(): Collection
    {
        return $this->otherRangesByManufacturer;
    }

    public function getRange(): CaravanRange
    {
        return $this->range;
    }

    public function bannerTitle(): string
    {
        $parts = [];
        if ($range = $this->getRange()) {
            $parts[] = $range->name;
        }

        return implode(' ', $parts);
    }

    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function getFormattedVideosForVue(): Collection
    {
        return $this->getVideos()->map(function (Video $video) {
            $presenter = (new VideoPresenter())->setWrappedObject($video);
            $video->imageUrl = $presenter->getFirstMediaUrl('image', 'responsiveIndex');
            $video->embedCodeUrl = $presenter->embedCodeUrl();
            return $video;
        });
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function getFormattedReviewsForVue(): Collection
    {
        return $this->getReviews()->map(function (Review $review) {
            $presenter = (new ReviewPresenter())->setWrappedObject($review);
            $review = $presenter->getWrappedObject();
            $review->imageUrl = $presenter->getFirstMediaUrl('image', 'responsiveIndex');
            $review->formattedDate = $presenter->formattedDate();
            $review->linkUrl = $presenter->linkUrl();
            return $review;
        });
    }

    public function getBrochures(): Collection
    {
        return $this->brochures;
    }

    public function getNewsletterSignUpForm(): ?Form
    {
        return $this->newsletterSignUpForm;
    }

    public function getSpecialOffers(): Collection
    {
        return $this->specialOffers;
    }

    public function imagesForGallery(string $galleryName): Collection
    {
        return $this->getGalleryImages()->filter(function ($image) use ($galleryName) {
            return $image->collection_name == $galleryName;
        });
    }

    public function firstEligiblePopUp(): ?PopUp
    {
        $eligiblePagePopUp = parent::firstEligiblePopUp();
        $eligibleCaravanRangePopUp = $this->firstEligibleCaravanRangePopUp();

        /**
         * Prioritise a page pop up over caravan range pop up
         */
        return !is_null($eligiblePagePopUp) ? $eligiblePagePopUp : $eligibleCaravanRangePopUp;
    }

    public function getRangeFilter(): string
    {
        return AbstractStockItemQueryBuilder::FILTER_RANGE;
    }

    public function getUsedStockFilter(): string
    {
        return AbstractStockItemQueryBuilder::STATUS_USED_STOCK;
    }

    public function getRangeUsedStockSearchPageUrl(): string
    {
        $site = $this->getSite();
        $page = Page::forSite($site)->template(Page::TEMPLATE_CARAVAN_SEARCH)->first();

        if (is_null($page)) {
            return '';
        }

        $pageUrl = pageLink($page);

        return sprintf(
            '%s?%s=%s&%s=%s&%s=%s',
            $pageUrl,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_USED_STOCK,
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $this->getRange()->manufacturer->name,
            'search-term',
            $this->getRange()->name
        );
    }

    private function firstEligibleCaravanRangePopUp(): ?PopUp
    {
        $dismissedPopUpIds = $this->fetchDismissedPopUpIds();

        $query = PopUp::displayable()->whereHas('caravanRanges', function (Builder $query) {
            $query->where('caravan_range_id', $this->range->id);
        });

        if (!empty($dismissedPopUpIds)) {
            $query->whereNotIn('id', $dismissedPopUpIds);
        }

        return $query->first();
    }

    private function fetchReviews(): Collection
    {
        return $this->getRange()
            ->reviews()
            ->with('media')
            ->whereHas('sites', function ($query) {
                $query->where('id', $this->getSite()->id);
            })
            ->published()->notExpired()->orderBy('date', 'desc')->get();
    }

    private function fetchVideos(): Collection
    {
        return $this->getRange()->videos()
            ->with('media')
            ->join('pageable_site', 'pageable_site.pageable_id', '=', 'videos.id')
            ->where('pageable_site.site_id', $this->getSite()->id)
            ->distinct()
            ->published()->orderBy('published_at', 'desc')->get();
    }

    public function fetchDealers(): array
    {
        return Dealer::branch()
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->where('site_id', $this->getSite()->id)
            ->get()
            ->toArray();
    }

    public function fetchRangeDealers(): Collection
    {
        return $this->getRange()
            ->dealers()
            ->where('site_id', $this->getSite()->id)
            ->published()
            ->orderBy('published_at', 'desc')
            ->get();
    }

    private function fetchBrochures(): Collection
    {
        return $this->getRange()
            ->brochures()
            ->with('media')
            ->where('site_id', $this->getSite()->id)
            ->published()
            ->orderBy('published_at', 'desc')
            ->get();
    }

    private function getGalleryImages(): Collection
    {
        if (!isset($this->galleryImages)) {
            $this->galleryImages = $this->getRange()->galleryImages()->orderBy('file_name', 'asc')->get();
        }
        return $this->galleryImages;
    }

    private function fetchOtherRangesByManufacturer(Manufacturer $manufacturer, Site $site): Collection
    {
        return $manufacturer
            ->caravanRanges()
            ->where('id', '!=', $this->range->id)
            ->whereHas('sites', function ($query) use ($site) {
                $query->where('id', $site->id);
            })
            ->get();
    }

    private function fetchSpecialOffers(CaravanRange $range): Collection
    {
        $site = $this->getSite();
        $caravanIds = $range->caravans->pluck('id');

        return SpecialOffer::forSite($site)
            ->displayable()
            ->orderedByPosition()
            ->whereHas('caravans', function (Builder $query) use ($caravanIds) {
                return $query->whereIn('id', $caravanIds);
            })
            ->get();
    }

    private function fetchNewsletterSignUpForm(): ?Form
    {
        return Form::where('type', Form::TYPE_NEWSLETTER_SIGN_UP)->first();
    }
}

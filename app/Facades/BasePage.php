<?php

namespace App\Facades;

use App\Facades\Article\ListingPage as ArticleListingPage;
use App\Facades\Article\ShowPage as ArticleShowPage;
use App\Facades\Brochure\ByPostPage as BrochureByPostPage;
use App\Facades\Brochure\ListingPage as BrochureListingPage;
use App\Facades\Dealer\ListingPage as DealerListingPage;
use App\Facades\Dealer\ShowPage as DealerShowPage;
use App\Facades\Event\ListingPage as EventListingPage;
use App\Facades\Event\ShowPage as EventShowPage;
use App\Facades\UsefulLink\ListingPage as UsefulLinkListingPage;
use App\Facades\Video\ListingPage as VideoListingPage;
use App\Facades\Video\ShowPage as VideoShowPage;
use App\Facades\SpecialOffer\ListingPage as SpecialOfferListingPage;
use App\Facades\SpecialOffer\CaravanShowPage as SpecialOfferCaravanShowPage;
use App\Facades\SpecialOffer\MotorhomeShowPage as SpecialOfferMotorhomeShowPage;
use App\Facades\Testimonial\ListingPage as TestimonialListingPage;
use App\Facades\Manufacturer\CaravansPage as ManufacturerCaravansPage;
use App\Facades\Manufacturer\MotorhomesPage as ManufacturerMotorhomesPage;
use App\Facades\StockItem\CaravanPage as CaravanStockPage;
use App\Facades\StockItem\MotorhomePage as MotorhomeStockPage;
use App\Facades\Review\ListingPage as ReviewListingPage;
use App\Facades\Vacancy\ListingPage as VacancyListingPage;
use App\Facades\Vacancy\ShowPage as VacancyShowPage;
use App\Facades\TellUsYourStory\ListingPage as TellUsYourStoryPage;
use App\Facades\NewsAndInfoLanderPage;
use App\Http\Middleware\EncryptCookies;
use App\Models\Article;
use App\Models\Brochure;
use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\Event;
use App\Models\Form;
use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\PopUp;
use App\Models\Review;
use App\Models\Site;
use App\Models\SpecialOffer;
use App\Models\Testimonial;
use App\Models\UsefulLink;
use App\Models\Vacancy;
use App\Models\Video;
use App\Models\VideoBanner;
use App\Presenters\Page\BasePagePresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

abstract class BasePage
{
    private Page $page;
    private Collection $areas;
    private Site $site;
    private Request $request;
    private ?Page $newsletterSignUpPage;

    public function __construct(Page $page, Request $request)
    {
        $this->page = $page;
        $this->request = $request;
        $this->site = $page->site ?: new Site();
        $this->areas = $this->fetchAreas($page);
    }

    public function adminLink(): string
    {
        return route('admin.dashboard');
    }

    public function getNewsletterSignUpPage(): ?Page
    {
        if (!isset($this->newsletterSignUpPage)) {
            $this->newsletterSignUpPage = Page::where('site_id', $this->getSite()->id)
                ->select('id', 'slug', 'parent_id')
                ->variety(Page::VARIETY_NEWSLETTER_SIGN_UP)
                ->with('parent:id,slug')
                ->first();
        }
        return $this->newsletterSignUpPage;
    }

    public function presentPage(Page $page): BasePagePresenter
    {
        $presenterClass = $page->getPresenterClass();
        return (new $presenterClass())->setWrappedObject($page);
    }

    public function templatePath(): string
    {
        return "site.pages." . $this->getPage()->template . ".main";
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function getAreas(string $holder = null): Collection
    {
        if (is_null($holder)) {
            return $this->areas;
        }
        return $this->areas->filter(function ($area) use ($holder) {
            return $area->holder === $holder;
        });
    }

    public function fetchAreas(Page $page): Collection
    {
        return $page->areas()->live()->published()->notExpired()->orderBy('position', 'asc')
            ->with([
                'panels' => function ($query) {
                    $query->live()->published()->notExpired()
                        ->with('area', 'media')
                        ->with(['featureable' => function (MorphTo $morphTo) {
                            $morphTo->morphWith([
                                Form::class => [
                                    'fieldsets' => function ($query) {
                                        $query->with([
                                            'fields' => function ($fieldsQuery) {
                                                $fieldsQuery->orderBy('position', 'asc');
                                            },
                                        ]);
                                    }
                                ],
                            ]);
                        }])->orderBy('position', 'asc');
                }
            ])->get();
    }

    public function getMetaTitle(): string
    {
        return $this->getPage()->meta_title ?? $this->getPage()->name ?? '';
    }

    public function getMetaDescription(): ?string
    {
        return $this->getPage()->meta_description;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function videoBanner(): ?VideoBanner
    {
        return $this->getPage()
            ->videoBanner()
            ->live()
            ->published()
            ->notExpired()
            ->first();
    }

    public function imageBanners(): Collection
    {
        return $this->getPage()
            ->imageBanners()
            ->live()
            ->published()
            ->notExpired()
            ->with([
                'buttons' => function ($query) {
                    $query->with('linkPage')->orderBy('position', 'asc');
                },
            ])
            ->orderBy('position', 'asc')
            ->get();
    }

    public function firstEligiblePopUp(): ?PopUp
    {
        $pagePopUp = $this->fetchEligiblePopUpForPage();
        $allPagesPopUp = $this->fetchEligiblePopUpForAllPages();

        if (!is_null($pagePopUp)) {
            return $pagePopUp;
        }

        return $allPagesPopUp;
    }

    public function editPageableUrl(): string
    {
        $page = $this->getPage();
        $pageable = $page->pageable;
        if (is_null($pageable)) {
            return '';
        }

        switch (get_class($pageable)) {
            case Manufacturer::class:
                return route('admin.manufacturers.edit', [
                    'manufacturer' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Article::class:
                return route('admin.articles.edit', [
                    'article' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Brochure::class:
                return route('admin.brochures.edit', [
                    'brochure' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case CaravanRange::class:
                return route('admin.manufacturers.caravan-ranges.edit', [
                    'manufacturer' => $pageable->manufacturer,
                    'caravan_range' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Dealer::class:
                return route('admin.dealers.edit', [
                    'dealer' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Event::class:
                return route('admin.events.edit', [
                    'event' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case MotorhomeRange::class:
                return route('admin.manufacturers.motorhome-ranges.edit', [
                    'manufacturer' => $pageable->manufacturer,
                    'motorhome_range' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Review::class:
                return route('admin.reviews.edit', [
                    'review' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case SpecialOffer::class:
                return route('admin.special-offers.edit', [
                    'special_offer' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Testimonial::class:
                return route('admin.testimonials.edit', [
                    'testimonial' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case UsefulLink::class:
                return route('admin.useful-links.edit', [
                    'useful_link' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Vacancy::class:
                return route('admin.vacancies.edit', [
                    'vacancy' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            case Video::class:
                return route('admin.videos.edit', [
                    'video' => $pageable,
                    'redirect_url' => pageLink($page),
                ]);
            default:
                return '';
        }
    }

    public function pageableName(): string
    {
        $pageable = $this->getPage()->pageable;

        if (is_null($pageable)) {
            return '';
        }

        return class_basename($pageable);
    }

    public function adminViewAllLinkInfo(): ?array
    {
        switch ($this->getPage()->template) {
            case Page::TEMPLATE_ARTICLES_LISTING:
            case Page::TEMPLATE_TELL_US_YOUR_STORY_LISTING:
                return [
                    'url' => route('admin.articles.index'),
                    'name' => 'Admin View All Articles',
                ];
            case Page::TEMPLATE_BROCHURES_BY_POST:
            case Page::TEMPLATE_BROCHURES_LISTING:
                return [
                    'url' => route('admin.brochures.index'),
                    'name' => 'Admin View All Brochures',
                ];
            case Page::TEMPLATE_ARTICLES_LISTING:
                return [
                    'url' => route('admin.articles.index'),
                    'name' => 'Admin View All Articles',
                ];
            case Page::TEMPLATE_DEALERS_LISTING:
                return [
                    'url' => route('admin.dealers.index'),
                    'name' => 'Admin View All Dealers',
                ];
            case Page::TEMPLATE_EVENTS_LISTING:
                return [
                    'url' => route('admin.events.index'),
                    'name' => 'Admin View All Events',
                ];
            case Page::TEMPLATE_MANUFACTURER_CARAVANS:
                $manufacturer = $this->getPage()->pageable;
                if (is_null($manufacturer)) {
                    return null;
                }
                return [
                    'url' => route('admin.manufacturers.caravan-ranges.index', [
                        'manufacturer' => $manufacturer
                    ]),
                    'name' => 'Admin View All Ranges',
                ];
            case Page::TEMPLATE_MANUFACTURER_MOTORHOMES:
                $manufacturer = $this->getPage()->pageable;
                if (is_null($manufacturer)) {
                    return null;
                }
                return [
                    'url' => route('admin.manufacturers.motorhome-ranges.index', [
                        'manufacturer' => $manufacturer
                    ]),
                    'name' => 'Admin View All Ranges',
                ];
            case Page::TEMPLATE_NEW_CARAVANS:
            case Page::TEMPLATE_NEW_MOTORHOMES:
                return [
                    'url' => route('admin.manufacturers.index'),
                    'name' => 'Admin View All Manufacturers',
                ];
            case Page::TEMPLATE_TESTIMONIALS_LISTING:
                return [
                    'url' => route('admin.testimonials.index'),
                    'name' => 'Admin View All Testimonials',
                ];
            case Page::TEMPLATE_SPECIAL_OFFERS_LISTING:
                return [
                    'url' => route('admin.special-offers.index'),
                    'name' => 'Admin View All Special Offers',
                ];
            case Page::TEMPLATE_USEFUL_LINK_LISTING:
                return [
                    'url' => route('admin.useful-links.index'),
                    'name' => 'Admin View All Useful Links',
                ];
            case Page::TEMPLATE_VIDEOS_LISTING:
                return [
                    'url' => route('admin.videos.index'),
                    'name' => 'Admin View All Videos',
                ];
            case Page::TEMPLATE_REVIEWS_LISTING:
                return [
                    'url' => route('admin.reviews.index'),
                    'name' => 'Admin View All Reviews',
                ];
            case Page::TEMPLATE_VACANCIES_LISTING:
                return [
                    'url' => route('admin.vacancies.index'),
                    'name' => 'Admin View All Vacancies',
                ];
            case Page::TEMPLATE_CARAVAN_RANGE:
                $caravanRange = $this->getPage()->pageable;
                if (is_null($caravanRange)) {
                    return null;
                }
                return [
                    'url' => route('admin.caravan-ranges.caravans.index', [
                        'caravanRange' => $caravanRange
                    ]),
                    'name' => 'Admin View All Caravans',
                ];
            case Page::TEMPLATE_MOTORHOME_RANGE:
                $motorhomeRange = $this->getPage()->pageable;
                if (is_null($motorhomeRange)) {
                    return null;
                }
                return [
                    'url' => route('admin.motorhome-ranges.motorhomes.index', [
                        'motorhomeRange' => $motorhomeRange
                    ]),
                    'name' => 'Admin View All Motorhomes',
                ];
            default:
                return [];
        }
    }

    public function adminEditUrl(): string
    {
        return route('admin.pages.edit', [
            'page' => $this->getPage(),
            'redirect_url' => pageLink($this->getPage()),
        ]);
    }

    public function adminAreasUrl(): string
    {
        return route('admin.pages.areas.index', [
            'page' => $this->getPage(),
            'redirect_url' => $this->getPage(),
        ]);
    }

    public static function for(Page $page, Request $request): BasePage
    {
        switch ($page->template) {
            case Page::TEMPLATE_ARTICLES_LISTING:
                return new ArticleListingPage($page, $request);
            case Page::TEMPLATE_ARTICLE_SHOW:
                return new ArticleShowPage($page, $request);
            case Page::TEMPLATE_BROCHURES_BY_POST:
                return new BrochureByPostPage($page, $request);
            case Page::TEMPLATE_BROCHURES_LISTING:
                return new BrochureListingPage($page, $request);
            case Page::TEMPLATE_DEALERS_LISTING:
                return new DealerListingPage($page, $request);
            case Page::TEMPLATE_DEALER_SHOW:
                return new DealerShowPage($page, $request);
            case Page::TEMPLATE_EVENTS_LISTING:
                return new EventListingPage($page, $request);
            case Page::TEMPLATE_EVENT_SHOW:
                return new EventShowPage($page, $request);
            case Page::TEMPLATE_VIDEOS_LISTING:
                return new VideoListingPage($page, $request);
            case Page::TEMPLATE_VIDEO_SHOW:
                return new VideoShowPage($page, $request);
            case Page::TEMPLATE_NEW_CARAVANS:
                return new NewCaravansPage($page, $request);
            case Page::TEMPLATE_NEW_MOTORHOMES:
                return new NewMotorhomesPage($page, $request);
            case Page::TEMPLATE_CARAVAN_STOCK_ITEM:
                return new CaravanStockPage($page, $request);
            case Page::TEMPLATE_MOTORHOME_STOCK_ITEM:
                return new MotorhomeStockPage($page, $request);
            case Page::TEMPLATE_MANUFACTURER_CARAVANS:
                return new ManufacturerCaravansPage($page, $request);
            case Page::TEMPLATE_MANUFACTURER_MOTORHOMES:
                return new ManufacturerMotorhomesPage($page, $request);
            case Page::TEMPLATE_CARAVAN_SEARCH:
                return new CaravanSearchPage($page, $request);
            case Page::TEMPLATE_MOTORHOME_SEARCH:
                return new MotorhomeSearchPage($page, $request);
            case Page::TEMPLATE_MOTORHOME_RANGE:
                return new MotorhomeRangePage($page, $request);
            case Page::TEMPLATE_MOTORHOME_COMPARISON:
                return new MotorhomeComparisonPage($page, $request);
            case Page::TEMPLATE_CARAVAN_RANGE:
                return new CaravanRangePage($page, $request);
            case Page::TEMPLATE_CARAVAN_COMPARISON:
                return new CaravanComparisonPage($page, $request);
            case Page::TEMPLATE_TESTIMONIALS_LISTING:
                return new TestimonialListingPage($page, $request);
            case Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW:
                return new SpecialOfferCaravanShowPage($page, $request);
            case Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW:
                return new SpecialOfferMotorhomeShowPage($page, $request);
            case Page::TEMPLATE_SPECIAL_OFFERS_LISTING:
                return new SpecialOfferListingPage($page, $request);
            case Page::TEMPLATE_HOMEPAGE:
                return new HomepagePage($page, $request);
            case Page::TEMPLATE_REVIEWS_LISTING:
                return new ReviewListingPage($page, $request);
            case Page::TEMPLATE_USEFUL_LINK_LISTING:
                return new UsefulLinkListingPage($page, $request);
            case Page::TEMPLATE_VACANCIES_LISTING:
                return new VacancyListingPage($page, $request);
            case Page::TEMPLATE_NEWS_AND_INFO_LANDER:
                return new NewsAndInfoLanderPage($page, $request);
            case Page::TEMPLATE_VACANCY_SHOW:
                return new VacancyShowPage($page, $request);
            case Page::TEMPLATE_SEARCH:
                return new SearchPage($page, $request);
            case Page::TEMPLATE_TELL_US_YOUR_STORY_LISTING:
                return new TellUsYourStoryPage($page, $request);
            default:
                return new StandardPage($page, $request);
        }
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function perPage(): int
    {
        return $this->getRequest()->get('per_page', 12);
    }

    protected function fetchDismissedPopUpIds(): array
    {
        $dismissedPopUpIds = $this->request->cookie(EncryptCookies::DISMISSED_POP_UPS_COOKIE_NAME, []);

        if ([] === $dismissedPopUpIds) {
            return $dismissedPopUpIds;
        }

        if (!is_string($dismissedPopUpIds)) {
            return [];
        }

        return json_decode($dismissedPopUpIds);
    }

    protected function fetchEligiblePopUpForPage(): ?PopUp
    {
        $dismissedPopUpIds = $this->fetchDismissedPopUpIds();

        $query = PopUp::displayable()->whereHas('appearsOnPages', function (Builder $query) {
            $query->where('page_id', $this->page->id);
        });

        if (!empty($dismissedPopUpIds)) {
            $query->whereNotIn('id', $dismissedPopUpIds);
        }

        return $query->first();
    }

    protected function fetchEligiblePopUpForAllPages(): ?PopUp
    {
        $dismissedPopUpIds = $this->fetchDismissedPopUpIds();

        $query = PopUp::displayable()->where('appears_on_all_pages', true);

        if (!empty($dismissedPopUpIds)) {
            $query->whereNotIn('id', $dismissedPopUpIds);
        }

        return $query->first();
    }
}

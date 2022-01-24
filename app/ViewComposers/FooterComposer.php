<?php

namespace App\ViewComposers;

use App\Models\Article;
use App\Models\Cta;
use App\Models\Logo;
use App\Models\Navigation;
use App\Models\Page;
use App\Models\Site;
use App\Models\Testimonial;
use App\Services\Footer\CaravanLinksBuilder;
use App\Services\Footer\MotorhomeLinksBuilder;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Throwable;

class FooterComposer
{
    public const CTA_TYPES = [
        Cta::TYPE_EVENT,
        Cta::TYPE_STANDARD,
    ];

    private Site $currentSite;
    private ?Article $latestArticle;
    private ?Testimonial $latestTestimonial;
    private Collection $ctas;
    /**
     * @var Collection<\App\Models\Page> $pages
     */
    private Collection $pages;
    private Navigation $moreNavigation;
    private Navigation $legalNavigation;
    private Collection $footerLogos;

    public function __construct()
    {
        $this->currentSite = $this->fetchCurrentSite();
        $this->ctas = $this->fetchCtas();
        $this->pages = $this->fetchPages();
        $this->latestArticle = $this->fetchLatestArticle();
        $this->latestTestimonial = $this->fetchLatestTestimonial();
        $this->footerLogos = $this->fetchFooterLogos();
        $this->setNavigations($this->currentSite);
    }

    public function compose(View $view): void
    {
        $view->with([
            'caravanLinks' => $this->buildCaravanLinks(),
            'preferredDealerPage' => $this->preferredDealerPage(),
            'currentSite' => $this->currentSite,
            'ctas' => $this->ctas,
            'latestArticle' => $this->latestArticle,
            'latestTestimonial' => $this->latestTestimonial,
            'legalNavigation' => $this->legalNavigation,
            'motorhomeLinks' => $this->buildMotorhomeLinks(),
            'moreNavigation' => $this->moreNavigation,
            'newsListingPage' => $this->newsListingPage(),
            'newsletterSignUpPage' => $this->newsletterSignUpPage(),
            'testimonialListingPage' => $this->testimonialListingPage(),
            'tellUsYourStoryPage' => $this->tellUsYourStoryPage(),
            'locationsPage' => $this->locationsPage(),
            'footerLogos' => $this->footerLogos,
        ]);
    }

    private function buildCaravanLinks(): SupportCollection
    {
        $builder = new CaravanLinksBuilder($this->currentSite);
        return $builder->call();
    }

    private function buildMotorhomeLinks(): SupportCollection
    {
        $builder = new MotorhomeLinksBuilder($this->currentSite);
        return $builder->call();
    }

    private function newsListingPage(): ?Page
    {
        return $this->pages->first(function (Page $page) {
            return $page->hasTemplate(Page::TEMPLATE_ARTICLES_LISTING);
        });
    }

    private function testimonialListingPage(): ?Page
    {
        return $this->pages->first(function (Page $page) {
            return $page->hasTemplate(Page::TEMPLATE_TESTIMONIALS_LISTING);
        });
    }

    private function preferredDealerPage(): ?Page
    {
        return $this->pages->first(function (Page $page) {
            return $page->hasVariety(Page::VARIETY_PREFERRED_DEALER);
        });
    }

    private function newsletterSignUpPage(): ?Page
    {
        return $this->pages->first(function (Page $page) {
            return $page->hasVariety(Page::VARIETY_NEWSLETTER_SIGN_UP);
        });
    }

    private function tellUsYourStoryPage(): ?Page
    {
        return $this->pages->first(function (Page $page) {
            return $page->hasTemplate(Page::TEMPLATE_TELL_US_YOUR_STORY_LISTING);
        });
    }

    private function locationsPage(): ?Page
    {
        return $this->pages->first(function (Page $page) {
            return $page->hasTemplate(Page::TEMPLATE_DEALERS_LISTING);
        });
    }

    private function fetchCtas(): Collection
    {
        return Cta::with('media', 'page:id,parent_id,slug', 'page.parent:id,slug')
            ->where('site_id', $this->currentSite->id)
            ->whereIn('type', self::CTA_TYPES)
            ->orderBy('position', 'asc')->get();
    }

    private function fetchLatestArticle(): ?Article
    {
        return Article::join('pageable_site', function ($join) {
            $join->on('articles.id', '=', 'pageable_site.pageable_id')
                ->where('pageable_type', Article::class)
                ->where('site_id', $this->currentSite->id);
        })
        ->styles([Article::STYLE_NEWS])
        ->published()
        ->live()
        ->notExpired()
        ->orderBy('date', 'desc')
        ->select('id', 'title')
        ->first();
    }

    private function fetchLatestTestimonial(): ?Testimonial
    {
        return Testimonial::join('site_testimonial', 'testimonial_id', '=', 'site_testimonial.testimonial_id')
            ->where('site_testimonial.site_id', $this->currentSite->id)
            ->published()->orderBy('published_at', 'desc')
            ->select('id', 'content')->first();
    }

    /**
     * @return Collection<\App\Models\Page>
     */
    private function fetchPages(): Collection
    {
        return Page::where('site_id', $this->currentSite->id)->displayable()
            ->where(function ($query) {
                $query->whereIn('template', [
                    Page::TEMPLATE_ARTICLES_LISTING,
                    Page::TEMPLATE_TESTIMONIALS_LISTING,
                    Page::TEMPLATE_DEALERS_LISTING,
                    Page::TEMPLATE_TELL_US_YOUR_STORY_LISTING,
                ])
                ->orWhereIn('variety', [
                    Page::VARIETY_NEWSLETTER_SIGN_UP,
                    Page::VARIETY_PREFERRED_DEALER,
                ]);
            })->get();
    }

    private function fetchCurrentSite(): Site
    {
        try {
            return resolve('currentSite');
        } catch (Throwable $e) {
            return new Site();
        }
    }

    private function setNavigations(Site $site): void
    {
        $navs = $this->fetchNavigations($site);
        $this->moreNavigation = $this->findNavigationByType($navs, Navigation::TYPE_FOOTER_MORE);
        $this->legalNavigation = $this->findNavigationByType($navs, Navigation::TYPE_FOOTER_LEGAL);
    }

    /**
     * @param Collection<\App\Models\Navigation> $navs
     */
    private function findNavigationByType(Collection $navs, string $type): Navigation
    {
        $navigation = $navs->first(function ($nav) use ($type) {
            return $nav->type === $type;
        });
        if ($navigation) {
            return $navigation;
        }
        return new Navigation([
            'type' => $type,
        ]);
    }

    private function fetchNavigations(Site $site): Collection
    {
        $displayablePageIds = $this->displayablePageIds($site);
        return Navigation::where('site_id', $site->id)
            ->whereIn('type', [
                Navigation::TYPE_FOOTER_MORE,
                Navigation::TYPE_FOOTER_LEGAL,
            ])
            ->with([
                'navigationItems' => function ($query) use ($displayablePageIds) {
                    $query->with('page:id,slug,parent_id,template', 'page.parent:id,slug')
                        ->whereNull('parent_id')
                        ->displayable($displayablePageIds)
                        ->orderBy('display_order', 'asc');
                },
            ])->get();
    }

    private function displayablePageIds(Site $site): SupportCollection
    {
        return Page::forSite($site)->displayable()->toBase()->pluck('id');
    }

    private function fetchFooterLogos(): Collection
    {
        return Logo::footerLocation()->get();
    }
}

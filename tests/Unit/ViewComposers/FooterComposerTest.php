<?php

namespace Tests\Unit\ViewComposers;

use App\Models\Article;
use App\Models\Cta;
use App\Models\Navigation;
use App\Models\Page;
use App\Models\Site;
use App\Models\Testimonial;
use App\ViewComposers\FooterComposer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Tests\TestCase;

class FooterComposerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sets_footer_more_navigation(): void
    {
        $view = $this->makeView();

        $this->composeComposer($view);

        $nav = $view->moreNavigation;

        $this->assertInstanceOf(Navigation::class, $nav);
        $this->assertEquals(Navigation::TYPE_FOOTER_MORE, $nav->type);
    }

    public function test_sets_footer_more_navigation_when_exists(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $nav = factory(Navigation::class)->create([
            'type' => Navigation::TYPE_FOOTER_MORE,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $footerNav = $view->moreNavigation;

        $this->assertInstanceOf(Navigation::class, $nav);
        $this->assertEquals(Navigation::TYPE_FOOTER_MORE, $nav->type);
        $this->assertEquals($nav->id, $footerNav->id);
    }

    public function test_sets_footer_legal_navigation(): void
    {
        $view = $this->makeView();

        $this->composeComposer($view);

        $nav = $view->legalNavigation;

        $this->assertInstanceOf(Navigation::class, $nav);
        $this->assertEquals(Navigation::TYPE_FOOTER_LEGAL, $nav->type);
    }

    public function test_sets_footer_legal_navigation_when_exists(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $nav = factory(Navigation::class)->create([
            'type' => Navigation::TYPE_FOOTER_LEGAL,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $footerNav = $view->legalNavigation;

        $this->assertInstanceOf(Navigation::class, $nav);
        $this->assertEquals(Navigation::TYPE_FOOTER_LEGAL, $nav->type);
        $this->assertEquals($nav->id, $footerNav->id);
    }


    public function test_sets_motorhome_links(): void
    {
        $view = $this->makeView();

        $this->composeComposer($view);

        $links = $view->motorhomeLinks;

        $this->assertInstanceOf(Collection::class, $links);
    }

    public function test_sets_caravan_links(): void
    {
        $view = $this->makeView();

        $this->composeComposer($view);

        $links = $view->caravanLinks;

        $this->assertInstanceOf(Collection::class, $links);
    }

    public function test_sets_current_site(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $view = $this->makeView();

        $this->composeComposer($view);

        $site = $view->currentSite;
        $this->assertEquals($currentSite->id, $site->id);
    }

    public function test_gets_ctas(): void
    {
        $otherSite = factory(Site::class)->create();
        $otherSiteCta = factory(Cta::class)->create([
            'site_id' => $otherSite->id,
            'type' => Cta::TYPE_STANDARD,
        ]);
        $currentSite = factory(Site::class)->create();
        $currentSiteCta = factory(Cta::class)->create([
            'site_id' => $currentSite->id,
            'type' => Cta::TYPE_STANDARD,
        ]);
        $this->app->instance('currentSite', $currentSite);
        $view = $this->makeView();

        $this->composeComposer($view);

        $ctas = $view->ctas;
        $ctaIds = $ctas->map->id;
        $this->assertContains($currentSiteCta->id, $ctaIds);
        $this->assertNotContains($otherSiteCta->id, $ctaIds);
    }

    public function test_fetches_news_listing_page(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $newsListingPage = factory(Page::class)->create([
            'template' => Page::TEMPLATE_ARTICLES_LISTING,
            'site_id' => $currentSite->id,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $page = $view->newsListingPage;
        $this->assertEquals($newsListingPage->id, $page->id);
    }

    public function test_fetches_locations_page(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $locationsPage = factory(Page::class)->create([
            'template' => Page::TEMPLATE_DEALERS_LISTING,
            'site_id' => $currentSite->id,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $page = $view->locationsPage;
        $this->assertEquals($locationsPage->id, $page->id);
    }

    public function test_fetches_testimonial_listing_page(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $testimonialPage = factory(Page::class)->create([
            'template' => Page::TEMPLATE_TESTIMONIALS_LISTING,
            'site_id' => $currentSite->id,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $page = $view->testimonialListingPage;
        $this->assertEquals($testimonialPage->id, $page->id);
    }

    public function test_fetches_newsletter_signup_page(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $newsletterPage = factory(Page::class)->create([
            'variety' => Page::VARIETY_NEWSLETTER_SIGN_UP,
            'site_id' => $currentSite->id,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $page = $view->newsletterSignUpPage;
        $this->assertEquals($newsletterPage->id, $page->id);
    }

    public function test_fetches_tell_us_your_story_page(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $tellUsYourStoryPage = factory(Page::class)->create([
            'template' => Page::TEMPLATE_TELL_US_YOUR_STORY_LISTING,
            'site_id' => $currentSite->id,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $page = $view->tellUsYourStoryPage;
        $this->assertEquals($tellUsYourStoryPage->id, $page->id);
    }

    public function test_fetches_preferred_dealer_page(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $preferredDealerPage = factory(Page::class)->create([
            'variety' => Page::VARIETY_PREFERRED_DEALER,
            'site_id' => $currentSite->id,
        ]);
        $view = $this->makeView();

        $this->composeComposer($view);

        $page = $view->preferredDealerPage;
        $this->assertEquals($preferredDealerPage->id, $page->id);
    }

    public function test_latest_article(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $article = factory(Article::class)->state('news')->create([
            'published_at' => Carbon::yesterday(),
            'live' => true,
        ]);
        $article->sites()->attach($currentSite);
        $view = $this->makeView();

        $this->composeComposer($view);

        $latestArticle = $view->latestArticle;
        $this->assertEquals($article->id, $latestArticle->id);
    }

    public function test_latest_testimonial(): void
    {
        $currentSite = factory(Site::class)->create();
        $this->app->instance('currentSite', $currentSite);
        $testimonial = factory(Testimonial::class)->create([
            'published_at' => Carbon::yesterday(),
        ]);
        $testimonial->sites()->attach($currentSite);
        $view = $this->makeView();

        $this->composeComposer($view);

        $latestTestimonial = $view->latestTestimonial;
        $this->assertEquals($testimonial->id, $latestTestimonial->id);
    }

    private function makeView(): View
    {
        return ViewFacade::make('layouts.footer.main');
    }

    private function composeComposer(View $view): void
    {
        $composer = new FooterComposer();
        $composer->compose($view);
    }
}

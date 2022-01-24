<?php

namespace App\Facades\Article;

use App\Facades\BasePage;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Dealer;
use App\Models\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ListingPage extends BasePage
{
    private Paginator $articles;
    private EloquentCollection $articleCategories;
    private EloquentCollection $dealers;
    private Collection $rangeNames;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->articles = $this->fetchArticles();
        $this->articleCategories = $this->fetchArticleCategories();
        $this->dealers = $this->fetchDealers();
        $this->rangeNames = $this->fetchRangeNames();
    }

    public function title(): string
    {
        return 'Latest News';
    }

    public function subtitle(): string
    {
        $newsletterSignUpPage = $this->getNewsletterSignUpPage();
        $parts = ['See below for the latest news across the Marquis Group.'];
        if ($newsletterSignUpPage) {
            $presenter = $this->presentPage($newsletterSignUpPage);
            $parts[] = 'If you would like to be kept up to date with this and details of';
            $parts[] = 'any events we are holding simply click';
            $parts[] = '<a href="' . $presenter->link() . '" class="text-endeavour hover:text-shiraz">here</a>';
            $parts[] = 'and register your email address with us';
        }
        return implode(' ', $parts);
    }

    public function getArticles(): Paginator
    {
        return $this->articles;
    }

    public function getArticleCategories(): EloquentCollection
    {
        return $this->articleCategories;
    }

    public function getDealers(): EloquentCollection
    {
        return $this->dealers;
    }

    public function getRangeNames(): Collection
    {
        return $this->rangeNames;
    }

    private function fetchArticles(): Paginator
    {
        $request = $this->getRequest();
        $query = $this->baseQuery();

        // Filter by inputs that have columns on articles
        $query->ransack($request->all());

        $articleCategoryIds = array_filter($request->get('article_category_ids', []));
        $rangeNames = array_filter($request->get('range_names', []));

        if (!empty($articleCategoryIds)) {
            $query->join('article_article_category', 'articles.id', '=', 'article_article_category.article_id')
                ->whereIn('article_article_category.article_category_id', $articleCategoryIds);
        }

        if (!empty($rangeNames)) {
            $query->join('article_caravan_range', 'articles.id', '=', 'article_caravan_range.article_id')
                ->join('caravan_ranges', 'article_caravan_range.caravan_range_id', '=', 'caravan_ranges.id')
                ->join('article_motorhome_range', 'articles.id', '=', 'article_motorhome_range.article_id')
                ->join('motorhome_ranges', 'article_motorhome_range.motorhome_range_id', '=', 'motorhome_ranges.id')
                ->where(function ($query) use ($rangeNames) {
                    $query->whereIn('caravan_ranges.name', $rangeNames)
                        ->orWhereIn('motorhome_ranges.name', $rangeNames);
                })
                ->distinct();
        }

        return $query->orderBy('date', 'desc')->select('articles.*')->paginate($this->perPage());
    }

    private function baseQuery(): Builder
    {
        $articleIds = $this->displayableArticleids();
        return Article::with('media')
            ->with([
                'pages' => function ($query) {
                    $query->forSite($this->getSite())
                        ->with('parent:id,slug')
                        ->select('id', 'parent_id', 'slug', 'site_id', 'pageable_type', 'pageable_id');
                },
            ])
            ->styles([
                Article::STYLE_NEWS,
                Article::STYLE_BOTH,
            ])
            ->published()
            ->live()
            ->notExpired()
            ->whereIn('articles.id', $articleIds);
    }

    private function displayableArticleIds(): Collection
    {
        return Page::forSite($this->getSite())
            ->where('pageable_type', Article::class)
            ->displayable()->toBase()->pluck('pageable_id');
    }

    private function fetchArticleCategories(): EloquentCollection
    {
        return ArticleCategory::orderBy('name', 'asc')->select('id', 'name')->get();
    }

    private function fetchDealers(): EloquentCollection
    {
        $dealerIds = $this->fetchDealerIds();

        return Dealer::where('site_id', $this->getSite()->id)
            ->whereIn('id', $dealerIds)
            ->orderBy('name', 'asc')->get();
    }

    private function fetchDealerIds(): Collection
    {
        return Article::join('pageable_site', 'articles.id', '=', 'pageable_site.pageable_id')
            ->where('pageable_site.site_id', $this->getSite()->id)
            ->distinct()
            ->toBase()->pluck('dealer_id');
    }

    private function fetchRangeNames(): Collection
    {
        $caravanRangesQuery = DB::table('caravan_ranges')->select('name');

        return DB::table('motorhome_ranges')->select('name')
            ->union($caravanRangesQuery)
            ->distinct()
            ->orderBy('name', 'asc')
            ->pluck('name');
    }
}

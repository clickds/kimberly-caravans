<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Articles\StoreRequest;
use App\Http\Requests\Admin\Articles\UpdateRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Traits\ImageDeletable;
use App\Services\Article\Saver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticlesController extends BaseController
{
    use ImageDeletable;

    public function index(Request $request): View
    {
        $articles = Article::with('pages')->ransack($request->all());
        $articleCategoryId = $request->input('article_category_id');
        if (!empty($articleCategoryId)) {
            $articles->whereHas('articleCategories', function ($query) use ($articleCategoryId) {
                $query->where('id', $articleCategoryId);
            });
        }
        $statusFilter = $request->input('status') ?: '';

        $articles = $articles->status($statusFilter);

        $articles = $articles->orderable($request->input('sort_by', 'published_at_desc'))
            ->paginate(20);

        return view('admin.articles.index', [
            'articles' => $articles,
            'categories' => ArticleCategory::orderBy('name', 'asc')->get(),
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_ARTICLES_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.articles.create', [
            'article' => new Article(),
            'articleCategories' => $this->fetchArticleCategories(),
            'articleCategoryIds' => [],
            'sites' => $this->fetchSites(),
            'caravanRangeIds' => [],
            'caravanRanges' => $this->fetchCaravanRanges(),
            'dealers' => $this->fetchDealers(),
            'motorhomeRangeIds' => [],
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'types' => $this->fetchTypes(),
            'styles' => $this->fetchStyles(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $article = new Article();

        if ($this->saveArticle($article, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Article created');
            }

            return redirect()->route('admin.articles.index')->with('success', 'Article created');
        }

        return back()->withInput($request->all())->with('error', 'Failed to create article');
    }

    public function edit(Article $article, Request $request): View
    {
        return view('admin.articles.edit', [
            'article' => $article,
            'articleCategories' => $this->fetchArticleCategories(),
            'articleCategoryIds' => $article->articleCategories()->pluck('id')->toArray(),
            'sites' => $this->fetchSites(),
            'caravanRangeIds' => $article->caravanRanges()->pluck('id')->toArray(),
            'caravanRanges' => $this->fetchCaravanRanges(),
            'dealers' => $this->fetchDealers(),
            'motorhomeRangeIds' => $article->motorhomeRanges()->pluck('id')->toArray(),
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'types' => $this->fetchTypes(),
            'styles' => $this->fetchStyles(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(Article $article, UpdateRequest $request): RedirectResponse
    {
        if ($this->saveArticle($article, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Article updated');
            }

            return redirect()->route('admin.articles.index')->with('success', 'Article updated');
        }

        return back()->withInput($request->all())->with('warning', 'Error, please try again');
    }

    public function destroy(Article $article, Request $request): RedirectResponse
    {
        $article->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Article deleted');
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted');
    }

    private function saveArticle(Article $article, FormRequest $request): bool
    {
        $saver = new Saver($article, $request);
        return $saver->call();
    }

    private function fetchArticleCategories(): Collection
    {
        return ArticleCategory::orderBy('name', 'asc')->get();
    }

    private function fetchCaravanRanges(): Collection
    {
        return CaravanRange::with('manufacturer')->orderBy('name', 'asc')
            ->select('name', 'id', 'manufacturer_id')->get();
    }

    private function fetchMotorhomeRanges(): Collection
    {
        return MotorhomeRange::with('manufacturer')->orderBy('name', 'asc')
            ->select('name', 'id', 'manufacturer_id')->get();
    }

    private function fetchDealers(): Collection
    {
        return Dealer::orderBy('name', 'asc')->select('name', 'id')->get();
    }

    private function fetchTypes(): array
    {
        return Article::TYPES;
    }

    private function fetchStyles(): array
    {
        return Article::STYLES;
    }
}

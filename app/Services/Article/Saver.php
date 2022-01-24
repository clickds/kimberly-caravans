<?php

namespace App\Services\Article;

use App\Models\Article;
use App\Models\Site;
use App\Services\Pageable\ArticlePageSaver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Saver
{
    private Article $article;
    private FormRequest $request;

    public function __construct(Article $article, FormRequest $request)
    {
        $this->article = $article;
        $this->request = $request;
    }

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $this->updateArticle();
            $this->addImage();
            $this->syncArticleCategories();
            $this->syncCaravanRanges();
            $this->syncMotorhomeRanges();
            $this->syncSites();

            DB::commit();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }

    private function updateArticle(): void
    {
        $article = $this->getArticle();
        $article->fill($this->validatedData());
        $article->save();
    }

    private function addImage(): void
    {
        $article = $this->getArticle();
        $request = $this->getRequest();

        if ($request->hasFile('image')) {
            $article->addMediaFromRequest('image')->toMediaCollection('image');
        }
    }

    private function syncArticleCategories(): void
    {
        $article = $this->getArticle();
        $request = $this->getRequest();

        $articleCategoryIds = $request->input('article_category_ids', []);
        $article->articleCategories()->sync($articleCategoryIds);
    }

    private function syncCaravanRanges(): void
    {
        $article = $this->getArticle();
        $request = $this->getRequest();

        $caravanRangeIds = $request->input('caravan_range_ids', []);
        $article->caravanRanges()->sync($caravanRangeIds);
    }

    private function syncMotorhomeRanges(): void
    {
        $article = $this->getArticle();
        $request = $this->getRequest();

        $motorhomeRangeIds = $request->input('motorhome_range_ids', []);
        $article->motorhomeRanges()->sync($motorhomeRangeIds);
    }

    private function syncSites(): void
    {
        $article = $this->getArticle();
        $request = $this->getRequest();

        $siteIds = $request->input('site_ids', []);
        $article->sites()->sync($siteIds);
        $this->updateSitePages($article, $siteIds);
    }

    private function updateSitePages(Article $article, array $siteIds): void
    {
        $article->pages()->whereNotIn('site_id', $siteIds)->delete();
        $sites = Site::whereIn('id', $siteIds)->get();
        foreach ($sites as $site) {
            $saver = new ArticlePageSaver($article, $site);
            $saver->call();
        }
    }

    private function validatedData(): array
    {
        return $this->getRequest()->validated();
    }

    private function getArticle(): Article
    {
        return $this->article;
    }

    private function getRequest(): FormRequest
    {
        return $this->request;
    }
}

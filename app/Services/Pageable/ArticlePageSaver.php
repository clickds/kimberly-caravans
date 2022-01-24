<?php

namespace App\Services\Pageable;

use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Article;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Facades\Log;

class ArticlePageSaver
{
    /**
     * @var \App\Models\Site
     */
    private $site;
    /**
     * @var \App\Models\Article
     */
    private $article;

    public function __construct(Article $article, Site $site)
    {
        $this->article = $article;
        $this->site = $site;
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();
            $this->saveArticlePage();
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
        }
    }

    private function saveArticlePage(): void
    {
        $page = $this->findOrInitializeArticlePage();
        $page->name = $this->getArticle()->title;
        $page->meta_title = $this->getArticle()->title;
        $page->parent_id = $this->findOrCreateArticleListingsPage()->id;
        $page->save();
    }

    private function findOrInitializeArticlePage(): Page
    {
        return $this->getArticle()->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => Page::TEMPLATE_ARTICLE_SHOW,
        ]);
    }

    private function findOrCreateArticleListingsPage(): Page
    {
        return Page::firstOrCreate(
            [
                'site_id' => $this->getSite()->id,
                'template' => Page::TEMPLATE_ARTICLES_LISTING,
            ],
            [
                'name' => 'News',
                'meta_title' => 'News',
            ]
        );
    }

    private function getArticle(): Article
    {
        return $this->article;
    }

    private function getSite(): Site
    {
        return $this->site;
    }
}

<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;
use App\Models\Page;
use App\Models\Site;
use App\Facades\Article\ShowPage;

class ArticlePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $parentPage = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_ARTICLES_LISTING,
        ]);
        $article = factory(Article::class)->create();
        $page = $article->pages()->create([
            'parent_id' => $parentPage->id,
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_ARTICLE_SHOW,
            'name' => $article->title,
            'meta_title' => $article->title,
        ]);

        $response = $this->get($parentPage->slug . '/' . $page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(ShowPage::class, $facade);
        $response->assertSee($article->title);
    }
}

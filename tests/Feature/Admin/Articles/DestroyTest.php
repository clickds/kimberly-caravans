<?php

namespace Tests\Feature\Admin\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $article = $this->createArticle();

        $response = $this->submit($article);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    public function test_removes_site_pages()
    {
        $site = $this->createSite();
        $article = $this->createArticle();
        $article->sites()->sync($site);
        $page = $this->createPageForPageable($article, $site);

        $response = $this->submit($article);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => Article::class,
            'pageable_id' => $article->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function submit(Article $article)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($article);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Article $article)
    {
        return route('admin.articles.destroy', $article);
    }

    private function createArticle()
    {
        return factory(Article::class)->create();
    }
}

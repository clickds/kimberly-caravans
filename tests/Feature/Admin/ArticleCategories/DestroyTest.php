<?php

namespace Tests\Feature\Admin\ArticleCategories;

use App\Models\ArticleCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $articleCategory = $this->createArticleCategory();

        $response = $this->submit($articleCategory);

        $response->assertRedirect(route('admin.article-categories.index'));
        $this->assertDatabaseMissing('article_categories', [
            'id' => $articleCategory->id,
        ]);
    }

    private function submit(ArticleCategory $articleCategory)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($articleCategory);

        return $this->actingAs($admin)->delete($url);
    }

    private function createArticleCategory($attributes = [])
    {
        return factory(ArticleCategory::class)->create($attributes);
    }

    private function url(ArticleCategory $articleCategory)
    {
        return route('admin.article-categories.destroy', $articleCategory);
    }
}

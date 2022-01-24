<?php

namespace Tests\Feature\Admin\ArticleCategories;

use App\Models\ArticleCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $articleCategory = $this->createArticleCategory();
        $data = $this->validFields([
            'name' => 'some name',
        ]);

        $response = $this->submit($articleCategory, $data);

        $response->assertRedirect(route('admin.article-categories.index'));
        $this->assertDatabaseHas('article_categories', [
            'name' => 'some name',
        ]);
    }

    public function test_it_requires_a_name()
    {
        $articleCategory = $this->createArticleCategory();
        $data = $this->validFields(['name' => '']);

        $response = $this->submit($articleCategory, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_requires_a_unique_name()
    {
        $articleCategory = $this->createArticleCategory();
        $otherArticleCategory = factory(ArticleCategory::class)->create();
        $data = $this->validFields(['name' => $otherArticleCategory->name]);

        $response = $this->submit($articleCategory, $data);

        $response->assertSessionHasErrors('name');
    }

    private function submit(ArticleCategory $articleCategory, $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($articleCategory);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function createArticleCategory($attributes = [])
    {
        return factory(ArticleCategory::class)->create($attributes);
    }

    private function validFields($overrides = [])
    {
        return array_merge([
            'name' => 'some name',
        ], $overrides);
    }

    private function url(ArticleCategory $articleCategory)
    {
        return route('admin.article-categories.update', $articleCategory);
    }
}

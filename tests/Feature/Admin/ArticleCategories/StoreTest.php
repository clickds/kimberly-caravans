<?php

namespace Tests\Feature\Admin\ArticleCategories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ArticleCategory;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $data = $this->validFields([
            'name' => 'some name',
        ]);

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.article-categories.index'));
        $this->assertDatabaseHas('article_categories', [
            'name' => 'some name',
        ]);
    }

    public function test_it_requires_a_name()
    {
        $data = $this->validFields(['name' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_requires_a_unique_name()
    {
        $otherArticleCategory = factory(ArticleCategory::class)->create();
        $data = $this->validFields(['name' => $otherArticleCategory->name]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    private function submit($data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        return array_merge([
            'name' => 'some name',
        ], $overrides);
    }

    private function url()
    {
        return route('admin.article-categories.store');
    }
}

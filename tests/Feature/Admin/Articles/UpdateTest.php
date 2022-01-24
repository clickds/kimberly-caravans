<?php

namespace Tests\Feature\Admin\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Dealer;
use Illuminate\Support\Arr;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $article = $this->createArticle();
        $dealer = factory(Dealer::class)->create();
        $data = $this->validFields($article, [
            'dealer_id' => $dealer->id,
        ]);

        $response = $this->submit($article, $data);

        $response->assertRedirect(route('admin.articles.index'));
        $articleData = Arr::except($data, ['article_category_ids']);
        $this->assertDatabaseHas('articles', $articleData);
        $articleCategoryIds = Arr::get($data, 'article_category_ids', []);
        foreach ($articleCategoryIds as $id) {
            $this->assertDatabaseHas('article_article_category', [
                'article_id' => $article->id,
                'article_category_id' => $id,
            ]);
        }
    }

    public function test_syncing_sites()
    {
        $old_site = $this->createSite();
        $new_site = $this->createSite();
        $article = $this->createArticle();
        $article->sites()->sync($old_site);
        $page = $this->createPageForPageable($article, $old_site);

        $data = $this->validFields($article);
        $data['site_ids'] = [$new_site->id];

        $response = $this->submit($article, $data);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('pageable_site', [
            'pageable_type' => Article::class,
            'pageable_id' => $article->id,
            'site_id' => $new_site->id,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $new_site->id,
            'pageable_type' => Article::class,
            'pageable_id' => $article->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => Article::class,
            'pageable_id' => $article->id,
            'site_id' => $old_site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'site_id' => $old_site->id,
            'pageable_type' => Article::class,
            'pageable_id' => $article->id,
        ]);
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $article = $this->createArticle();
        $data = $this->validFields($article, [$inputName => '']);

        $response = $this->submit($article, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['title'],
            ['date'],
            ['excerpt'],
            ['article_category_ids'],
            ['style'],
            ['live'],
        ];
    }

    public function test_it_requires_published_at_to_be_a_time()
    {
        $article = $this->createArticle();
        $data = $this->validFields($article, ['published_at' => 'abc']);

        $response = $this->submit($article, $data);

        $response->assertSessionHasErrors('published_at');
    }

    /**
     * @dataProvider associationExistsProvider
     */
    public function test_association_required_validation(string $inputName)
    {
        $article = $this->createArticle();
        $data = $this->validFields($article, [
            $inputName => [0]
        ]);

        $response = $this->submit($article, $data);

        $response->assertSessionHasErrors($inputName . '.0');
    }

    public function associationExistsProvider(): array
    {
        return [
            ['article_category_ids'],
            ['caravan_range_ids'],
            ['motorhome_range_ids'],
        ];
    }

    private function submit(Article $article, $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($article);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields(Article $article, $overrides = [])
    {
        $defaults = [
            'title' => 'some name',
            'excerpt' => 'some excerpt',
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(Article::TYPES),
            'style' => $this->faker->randomElement(Article::STYLES),
            'live' => $this->faker->boolean(),
        ];

        if (!array_key_exists('article_category_ids', $overrides)) {
            $articleCategory = factory(ArticleCategory::class)->create();
            $defaults['article_category_ids'] = [$articleCategory->id];
        }

        return array_merge($defaults, $overrides);
    }

    private function url(Article $article)
    {
        return route('admin.articles.update', $article);
    }

    private function createArticle()
    {
        return factory(Article::class)->create();
    }
}

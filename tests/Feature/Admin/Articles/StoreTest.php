<?php

namespace Tests\Feature\Admin\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ArticleCategory;
use App\Models\Article;
use App\Models\Dealer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $this->fakeStorage();
        $dealer = factory(Dealer::class)->create();
        $data = $this->validFields([
            'dealer_id' => $dealer->id,
        ]);

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.articles.index'));
        $articleData = Arr::except($data, ['article_category_ids', 'image']);
        $this->assertDatabaseHas('articles', $articleData);
        $articleCategoryIds = Arr::get($data, 'article_category_ids', []);
        foreach ($articleCategoryIds as $id) {
            $this->assertDatabaseHas('article_article_category', [
                'article_category_id' => $id,
            ]);
        }
    }

    public function test_syncing_sites()
    {
        $this->fakeStorage();
        $site = $this->createSite();
        $data = $this->validFields();
        $data['site_ids'] = [$site->id];

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.articles.index'));
        $article = Article::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('pageable_site', [
            'pageable_type' => Article::class,
            'pageable_id' => $article->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $site->id,
            'pageable_type' => Article::class,
            'pageable_id' => $article->id,
        ]);
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validFields([$inputName => null]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['title'],
            ['date'],
            ['image'],
            ['excerpt'],
            ['article_category_ids'],
            ['style'],
            ['live'],
        ];
    }

    public function test_it_requires_published_at_to_be_a_time()
    {
        $data = $this->validFields(['published_at' => 'abc']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('published_at');
    }

    /**
     * @dataProvider associationExistsProvider
     */
    public function test_association_required_validation(string $inputName)
    {
        $data = $this->validFields([
            $inputName => [0]
        ]);

        $response = $this->submit($data);

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

    private function submit($data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'title' => 'some name',
            'excerpt' => 'some excerpt',
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(Article::TYPES),
            'image' => UploadedFile::fake()->image('blah.jpg'),
            'style' => $this->faker->randomElement(Article::STYLES),
            'live' => $this->faker->boolean(),
        ];

        if (!array_key_exists('article_category_ids', $overrides)) {
            $defaults['article_category_ids'] = [$this->createArticleCategory()->id];
        }

        return array_merge($defaults, $overrides);
    }

    private function url()
    {
        return route('admin.articles.store');
    }

    private function createArticleCategory()
    {
        return factory(ArticleCategory::class)->create();
    }
}

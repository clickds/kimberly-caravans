<?php

namespace Tests\Unit\Services\Pageable;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Article;
use App\Models\Page;
use App\Services\Pageable\ArticlePageSaver;

class ArticlePageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_article_pageable_creates_article_show_page()
    {
        $site = $this->createSite();
        $article = factory(Article::class)->create();
        $saver = new ArticlePageSaver($article, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $article->title,
            "pageable_type" => Article::class,
            "pageable_id" => $article->id,
            "template" => Page::TEMPLATE_ARTICLE_SHOW,
        ]);
    }
}

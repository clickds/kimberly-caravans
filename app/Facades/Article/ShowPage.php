<?php

namespace App\Facades\Article;

use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\Article;
use App\Models\Page;

class ShowPage extends BasePage
{
    /**
     * @var Article
     */
    private $article;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->article = $page->pageable;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }
}

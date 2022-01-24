<?php

namespace App\Facades;

use Illuminate\Http\Request;
use App\Models\Cta;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection as Collection;

class NewsAndInfoLanderPage extends BasePage
{
    private Collection $ctas;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);
        $this->ctas = $this->fetchCtas();
    }

    public function getCtas(): Collection
    {
        return $this->ctas;
    }

    public function fetchCtas(): Collection
    {
        $site = $this->getSite();
        return Cta::with('page:id,slug,parent_id', 'page.parent:id,slug')
            ->where('site_id', $site->id)
            ->where('type', Cta::TYPE_NEWS_AND_INFO_LANDER)
            ->orderBy('position', 'asc')
            ->get();
    }
}

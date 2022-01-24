<?php

namespace App\Presenters\Cta;

use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;

class NewsAndInfoLanderTypePresenter extends BasePresenter
{
    public function partialPath(): string
    {
        return 'ctas._news-and-info-lander';
    }

    public function page(): BasePagePresenter
    {
        $page = $this->getWrappedObject()->page;
        if (is_null($page)) {
            $page = new Page();
        }
        if (get_class($page) == BasePagePresenter::class) {
            return $page;
        }
        return (new BasePagePresenter())->setWrappedObject($page);
    }
}

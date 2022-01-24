<?php

namespace App\Presenters\Cta;

use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;

class StandardTypePresenter extends BasePresenter
{
    public function partialPath(): string
    {
        return 'ctas._standard';
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

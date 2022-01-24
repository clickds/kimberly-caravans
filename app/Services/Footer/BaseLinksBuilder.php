<?php

namespace App\Services\Footer;

use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;

abstract class BaseLinksBuilder
{
    protected ?Page $stockSearchPage;
    protected ?Page $newModelsPage;

    protected function buildPresenter(Page $page): BasePagePresenter
    {
        $presenter = new BasePagePresenter();
        $presenter->setWrappedObject($page);
        return $presenter;
    }

    protected function getStockSearchPage(): ?Page
    {
        return $this->stockSearchPage;
    }

    protected function getNewModelsPage(): ?Page
    {
        return $this->newModelsPage;
    }
}

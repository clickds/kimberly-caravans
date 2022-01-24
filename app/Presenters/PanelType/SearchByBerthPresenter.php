<?php

namespace App\Presenters\PanelType;

use App\Facades\CaravanSearchPage;
use App\Facades\MotorhomeSearchPage;
use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;
use Illuminate\Support\Collection;

class SearchByBerthPresenter extends BasePanelPresenter
{
    private const PAGE_TEMPLATES = [
        Page::TEMPLATE_CARAVAN_SEARCH,
        Page::TEMPLATE_MOTORHOME_SEARCH,
    ];

    private Collection $pages;

    public function getTitleContent(): string
    {
        $content = [];
        if ($this->displayMotorhomes()) {
            $content[] = 'Motorhome';
        }
        if ($this->displayCaravans()) {
            $content[] = 'Caravan';
        }

        return implode(' or ', $content);
    }

    public function getCaravanBerthOptions(): array
    {
        return CaravanSearchPage::BERTH_OPTIONS;
    }

    public function getCaravanSearchPage(): ?BasePagePresenter
    {
        $page = $this->getPages()->first(function ($page) {
            return $page->template === Page::TEMPLATE_CARAVAN_SEARCH;
        });
        return $this->instantiatePresenter($page);
    }

    public function getMotorhomeBerthOptions(): array
    {
        return MotorhomeSearchPage::BERTH_OPTIONS;
    }

    public function getMotorhomeSearchPage(): ?BasePagePresenter
    {
        $page = $this->getPages()->first(function ($page) {
            return $page->template === Page::TEMPLATE_MOTORHOME_SEARCH;
        });
        return $this->instantiatePresenter($page);
    }

    public function berthOptionQueryParameters(array $berthOption): array
    {
        $berthParams = array_filter($berthOption, function ($key) {
            return in_array($key, ['min', 'max']);
        }, ARRAY_FILTER_USE_KEY);

        return [
            'berths' => $berthParams,
        ];
    }

    private function instantiatePresenter(?Page $page = null): ?BasePagePresenter
    {
        if (is_null($page)) {
            return null;
        }
        $presenter = new BasePagePresenter();
        ;
        $presenter->setWrappedObject($page);
        return $presenter;
    }

    private function getPages(): Collection
    {
        if (!isset($this->pages)) {
            $this->pages = $this->fetchPages();
        }
        return $this->pages;
    }

    private function fetchPages(): Collection
    {
        $site = $this->getSite();
        return Page::forSite($site)->displayable()
            ->whereIn('template', self::PAGE_TEMPLATES)
            ->get();
    }
}

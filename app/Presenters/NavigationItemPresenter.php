<?php

namespace App\Presenters;

use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;
use McCool\LaravelAutoPresenter\BasePresenter;

class NavigationItemPresenter extends BasePresenter
{
    public function linkUrl(): string
    {
        $navigationItem = $this->getWrappedObject();

        if ($this->hasExternalUrl()) {
            return $navigationItem->external_url;
        }

        $pagePresenter = $navigationItem->page;
        if ($pagePresenter instanceof Page) {
            $pagePresenter = $this->buildPagePresenter($pagePresenter);
        }

        $pageLink = $pagePresenter->link([], false);

        if ($navigationItem->query_parameters) {
            $pageLink .= $navigationItem->query_parameters;
        }

        return $pageLink;
    }

    public function linkTarget(): string
    {
        if ($this->hasExternalUrl()) {
            return '_blank';
        }
        return '_self';
    }

    public function mainNavigationItemDesktopCssClasses(): string
    {
        $classString = 'leading-none px-4 xl:px-8 py-1 block h-full w-full flex items-center';

        switch ($this->getWrappedObject()->background_colour) {
            case 'shiraz':
                $classString .= ' bg-shiraz hover:bg-monarch hover:text-white';
                break;
            case 'white':
                $classString .= ' bg-white text-shiraz hover:bg-web-orange';
                break;
            default:
                $classString .= ' bg-shiraz hover:bg-monarch hover:text-white';
        }

        return $classString;
    }

    public function fullMenuMainItemContainerCssClasses(int $index): string
    {
        $classes = ['w-full', 'md:w-1/2', 'lg:w-1/3', 'md:px-2', 'md:mb-2'];

        switch ($index) {
            case 1:
                $classes[] = 'md:order-3';
                break;
            case 2:
                $classes[] = 'md:order-1';
                break;
            case 3:
                $classes[] = 'md:order-4';
                break;
            case 4:
                $classes[] = 'md:order-2';
                break;
            case 5:
                $classes[] = 'md:order-5';
                break;
            default:
                break;
        }

        return implode(' ', $classes);
    }

    public function iconPartialPath(): ?string
    {
        $page = $this->getWrappedObject()->page;
        if (is_null($page)) {
            return null;
        }
        switch ($page->template) {
            case Page::TEMPLATE_CARAVAN_SEARCH:
                return 'site.shared.svg-icons.caravan';
            case Page::TEMPLATE_NEW_CARAVANS:
                return 'site.shared.svg-icons.new-caravan';
            case Page::TEMPLATE_MOTORHOME_SEARCH:
                return 'site.shared.svg-icons.motorhome';
            case Page::TEMPLATE_NEW_MOTORHOMES:
                return 'site.shared.svg-icons.new-motorhome';
            case Page::TEMPLATE_DEALERS_LISTING:
                return 'site.shared.svg-icons.locations';
            case Page::TEMPLATE_SPECIAL_OFFERS_LISTING:
                return 'site.shared.svg-icons.offers';
            default:
                return null;
        }
    }

    private function hasExternalUrl(): bool
    {
        return !empty($this->getWrappedObject()->external_url);
    }

    private function buildPagePresenter(Page $page): BasePagePresenter
    {
        $presenter = new BasePagePresenter();
        $presenter->setWrappedObject($page);
        return $presenter;
    }
}

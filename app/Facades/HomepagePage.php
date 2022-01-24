<?php

namespace App\Facades;

use App\Models\Dealer;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

class HomepagePage extends BasePage
{
    /**
     * @var Collection<\App\Models\Page> $pages
     */
    private Collection $pages;

    public function getText(string $text): string
    {
        return $text;
    }

    public function specialOffersPage(): ?Page
    {
        return $this->getPages()->first(function ($page) {
            return $page->template === Page::TEMPLATE_SPECIAL_OFFERS_LISTING;
        });
    }

    public function dealersPage(): ?Page
    {
        return $this->getPages()->first(function ($page) {
            return $page->template === Page::TEMPLATE_DEALERS_LISTING;
        });
    }

    public function dealersCount(): int
    {
        return Dealer::where('site_id', $this->getSite()->id)->branch()->count();
    }

    public function aboutUsPage(): ?Page
    {
        return $this->getPages()->first(function ($page) {
            return $page->variety === Page::VARIETY_ABOUT_US;
        });
    }

    public function accessoriesPage(): ?Page
    {
        return $this->getPages()->first(function ($page) {
            return $page->variety === Page::VARIETY_ACCESSORIES;
        });
    }

    public function servicesPage(): ?Page
    {
        return $this->getPages()->first(function ($page) {
            return $page->variety === Page::VARIETY_SERVICES;
        });
    }

    /**
     * @return Collection<\App\Models\Page>
     */
    public function getPages(): Collection
    {
        if (!isset($this->pages)) {
            $this->pages = $this->fetchPages();
        }
        return $this->pages;
    }

    /**
     * @return Collection<\App\Models\Page>
     */
    private function fetchPages(): Collection
    {
        $pages = $this->fetchTemplatePages();
        $pages = $pages->merge($this->fetchVarietyPages());

        return $pages;
    }

    private function fetchTemplatePages(): Collection
    {
        $site = $this->getSite();
        $templates = [
            Page::TEMPLATE_DEALERS_LISTING,
            Page::TEMPLATE_SPECIAL_OFFERS_LISTING,
        ];

        return $site->pages()->whereIn('template', $templates)
            ->displayable()->get();
    }

    private function fetchVarietyPages(): Collection
    {
        $site = $this->getSite();
        $varieties = [
            Page::VARIETY_ABOUT_US,
            Page::VARIETY_SERVICES,
            Page::VARIETY_ACCESSORIES,
        ];

        return $site->pages()->whereIn('variety', $varieties)
            ->displayable()->get();
    }
}

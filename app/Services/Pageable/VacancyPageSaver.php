<?php

namespace App\Services\Pageable;

use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Facades\Log;

final class VacancyPageSaver
{
    public const VACANCIES_LISTING_PAGE_NAME = 'Vacancies';

    private Site $site;

    private Vacancy $vacancy;

    public function __construct(Vacancy $vacancy, Site $site)
    {
        $this->vacancy = $vacancy;
        $this->site = $site;
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();

            $vacancyListingPage = $this->findOrCreateVacancyListingPage();

            $vacancyPage = $this->findOrCreateVacancyPage();

            $vacancyPage->name = $this->vacancy->title;

            $vacancyPage->meta_title = $this->vacancy->title;

            $vacancyPage
                ->parent()
                ->associate($vacancyListingPage)
                ->save();

            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();
        }
    }

    private function findOrCreateVacancyListingPage(): Page
    {
        return Page::firstOrCreate(
            [
                'site_id' => $this->site->id,
                'template' => Page::TEMPLATE_VACANCIES_LISTING,
            ],
            [
                'name' => self::VACANCIES_LISTING_PAGE_NAME,
                'meta_title' => self::VACANCIES_LISTING_PAGE_NAME,
            ],
        );
    }

    private function findOrCreateVacancyPage(): Page
    {
        return $this->vacancy->pages()->firstOrNew([
            'site_id' => $this->site->id,
            'template' => Page::TEMPLATE_VACANCY_SHOW,
        ]);
    }
}

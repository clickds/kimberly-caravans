<?php

namespace App\Facades\Vacancy;

use App\Facades\BasePage;
use App\Models\Page;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ListingPage extends BasePage
{
    private Collection $vacancies;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->vacancies = $this->fetchVacancies();
    }

    public function getVacancies(): Collection
    {
        return $this->vacancies;
    }

    private function fetchVacancies(): Collection
    {
        return Vacancy::displayable()->orderBy('published_at', 'desc')->get();
    }
}

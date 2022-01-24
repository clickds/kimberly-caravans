<?php

namespace App\Facades\Vacancy;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use UnexpectedValueException;

class ShowPage extends BasePage
{
    private Vacancy $vacancy;
    private Collection $dealers;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        if (is_null($page->pageable) || !is_a($page->pageable, Vacancy::class)) {
            throw new UnexpectedValueException('Expected an instance of Vacancy');
        }

        $this->vacancy = $page->pageable;
        $this->dealers = $this->vacancy->dealers;
    }

    public function getVacancy(): Vacancy
    {
        return $this->vacancy;
    }

    public function getDealers(): Collection
    {
        return $this->dealers;
    }
}

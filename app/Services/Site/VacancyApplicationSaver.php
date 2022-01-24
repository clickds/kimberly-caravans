<?php

namespace App\Services\Site;

use App\Models\Vacancy;
use App\Models\VacancyApplication;
use Illuminate\Support\Arr;

final class VacancyApplicationSaver
{
    public function save(Vacancy $vacancy, array $applicationData): VacancyApplication
    {
        $application = $vacancy->applications()->create($applicationData);

        $this->saveEmploymentHistory($application, Arr::get($applicationData, 'employment_history', []));

        return $application;
    }

    private function saveEmploymentHistory(VacancyApplication $application, array $employmentHistoryData): void
    {
        foreach ($employmentHistoryData as $employmentHistoryDatum) {
            $application->employmentHistory()->create($employmentHistoryDatum);
        }
    }
}

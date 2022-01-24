<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\Vacancy;
use UnexpectedValueException;

final class VacancyDataProvider extends BaseDataProvider
{
    public const TYPE = 'Job Vacancy';

    protected function getContentData(): array
    {
        return [self::KEY_CONTENT => $this->generateContentString()];
    }

    protected function getTypeData(): array
    {
        return [self::KEY_TYPE => self::TYPE];
    }

    private function generateContentString(): string
    {
        $vacancy = $this->page->pageable;

        if (is_null($vacancy) || !is_a($vacancy, Vacancy::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of Vacancy');
        }

        return strip_tags($vacancy->description);
    }
}

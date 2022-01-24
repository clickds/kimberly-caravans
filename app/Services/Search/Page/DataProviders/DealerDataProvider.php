<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\Dealer;
use App\Presenters\DealerPresenter;
use UnexpectedValueException;

final class DealerDataProvider extends BaseDataProvider
{
    public const TYPE = 'Dealership';

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
        $dealer = $this->page->pageable;

        if (is_null($dealer) || !is_a($dealer, Dealer::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of Dealer');
        }

        $presenter = (new DealerPresenter())->setWrappedObject($dealer);

        return $presenter->getFormattedAddress();
    }
}

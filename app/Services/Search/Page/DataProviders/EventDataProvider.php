<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\Event;
use UnexpectedValueException;

final class EventDataProvider extends BaseDataProvider
{
    public const TYPE = 'Event';

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
        $event = $this->page->pageable;

        if (is_null($event) || !is_a($event, Event::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of Event');
        }

        return $event->summary;
    }
}

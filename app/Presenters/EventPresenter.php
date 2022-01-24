<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Psy\CodeCleaner\ImplicitReturnPass;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Dealer;
use App\Models\Event;
use App\Models\EventLocation;
use UnexpectedValueException;

class EventPresenter extends BasePresenter
{
    public function getImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }

    public function formattedDate(): string
    {
        $event = $this->getWrappedObject();
        $dates = [
            $event->start_date,
        ];
        if ($event->start_date->notEqualTo($event->end_date)) {
            $dates[] = $event->end_date;
        }
        $dates = array_filter($dates);
        $dates = array_map(function ($date) {
            return $date->format('j F Y');
        }, $dates);

        return implode(' - ', $dates);
    }

    public function venue(): string
    {
        $event = $this->getEvent();
        if ($dealer = $event->dealer) {
            return $dealer->name;
        }
        if ($eventLocation = $event->eventLocation) {
            return $eventLocation->name;
        }
        return "N/A";
    }

    public function venueAddress(): string
    {
        if ($this->isDealerEvent()) {
            return $this->getFormattedDealerAddress();
        } else {
            return $this->getFormattedEventLocationAddress();
        }
    }


    public function getLongitude(): float
    {
        if ($this->isDealerEvent()) {
            return $this->getEventDealer()->locations()->firstOrFail()->longitude;
        }

        return $this->getEventLocation()->longitude;
    }

    public function getLatitude(): float
    {
        if ($this->isDealerEvent()) {
            return $this->getEventDealer()->locations()->firstOrFail()->latitude;
        }

        return $this->getEventLocation()->latitude;
    }

    public function image(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }

    private function getEventDealer(): Dealer
    {
        $dealer = $this->getEvent()->dealer;

        if (is_null($dealer)) {
            throw new UnexpectedValueException('Expected an instance of Dealer.');
        }

        return $dealer;
    }

    private function getEventLocation(): EventLocation
    {
        $eventLocation = $this->getEvent()->eventLocation;

        if (is_null($eventLocation)) {
            throw new UnexpectedValueException('Expected an instance of EventLocation.');
        }

        return $eventLocation;
    }

    private function isDealerEvent(): bool
    {
        return !is_null($this->getEvent()->dealer);
    }

    private function getFormattedDealerAddress(): string
    {
        $dealerAddressData = $this->getEventDealer()
            ->locations()
            ->select(['line_1', 'line_2', 'town', 'county', 'postcode'])
            ->firstOrFail()
            ->toArray();

        return implode("\n", array_filter($dealerAddressData));
    }

    private function getFormattedEventLocationAddress(): string
    {
        $eventAddressData = $this->getEventLocation()
            ->select('address_line_1', 'address_line_2', 'town', 'county', 'postcode')
            ->firstOrFail()
            ->toArray();

        return implode("\n", array_filter($eventAddressData));
    }

    private function getEvent(): Event
    {
        return $this->getWrappedObject();
    }
}

<?php

namespace App\Presenters\PanelType;

use App\Models\Event;
use App\Presenters\EventPresenter as EventModelPresenter;
use UnexpectedValueException;

class EventPresenter extends BasePanelPresenter
{
    public function getEvent(): EventModelPresenter
    {
        $featureable = $this->getPanel()->featureable;

        if ($featureable instanceof Event) {
            return (new EventModelPresenter())->setWrappedObject($featureable);
        }

        if ($featureable instanceof EventModelPresenter) {
            return $featureable;
        }

        throw new UnexpectedValueException('Failed to get brochure');
    }

    public function linkUrl(): string
    {
        $site = $this->getSite();
        $eventPage = $this->getEvent()->sitePage($site);

        return pageLink($eventPage);
    }
}

<?php

namespace App\Presenters\PanelType;

use App\Models\Brochure;
use App\Presenters\BrochurePresenter as BrochureModelPresenter;
use UnexpectedValueException;

class BrochurePresenter extends BasePanelPresenter
{
    public function getBrochure(): BrochureModelPresenter
    {
        $featureable = $this->getPanel()->featureable;

        if ($featureable instanceof Brochure) {
            return (new BrochureModelPresenter())->setWrappedObject($featureable);
        }

        if ($featureable instanceof BrochureModelPresenter) {
            return $featureable;
        }

        throw new UnexpectedValueException('Failed to get brochure');
    }
}

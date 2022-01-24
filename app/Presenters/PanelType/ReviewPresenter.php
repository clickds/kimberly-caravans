<?php

namespace App\Presenters\PanelType;

use App\Models\Review;
use App\Presenters\ReviewPresenter as ReviewModelPresenter;
use UnexpectedValueException;

class ReviewPresenter extends BasePanelPresenter
{
    public function getReview(): ReviewModelPresenter
    {
        $featureable = $this->getPanel()->featureable;

        if ($featureable instanceof Review) {
            return (new ReviewModelPresenter())->setWrappedObject($featureable);
        }

        if ($featureable instanceof ReviewModelPresenter) {
            return $featureable;
        }

        throw new UnexpectedValueException('Failed to get review');
    }
}

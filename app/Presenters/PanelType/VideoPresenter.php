<?php

namespace App\Presenters\PanelType;

use App\Models\Video;
use App\Presenters\VideoPresenter as MainVideoPresenter;

class VideoPresenter extends BasePanelPresenter
{
    public function getVideo(): ?Video
    {
        $video = $this->getPanel()->featureable;
        if (get_class($video) == Video::class) {
            return $video;
        } elseif (get_class($video) == MainVideoPresenter::class) {
            return $video->getWrappedObject();
        }
        return null;
    }
}

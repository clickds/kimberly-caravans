<?php

namespace App\Presenters\PanelType;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImagePanelPresenter extends BasePanelPresenter
{
    public function getImage(): ?Media
    {
        return $this->getPanel()->getFirstMedia('image');
    }

    public function getLinkUrl(): ?string
    {
        $page = $this->getPanel()->page;
        $externalUrl = $this->getPanel()->external_url;

        if (is_null($page) && is_null($externalUrl)) {
            return null;
        }

        if ($page) {
            return pageLink($page);
        }

        return $externalUrl;
    }
}

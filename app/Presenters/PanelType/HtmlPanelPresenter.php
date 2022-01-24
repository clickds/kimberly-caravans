<?php

namespace App\Presenters\PanelType;

class HtmlPanelPresenter extends BasePanelPresenter
{
    public function getHtmlContent(): ?string
    {
        return $this->getPanel()->html_content;
    }
}

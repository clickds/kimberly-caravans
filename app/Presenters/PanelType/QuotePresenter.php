<?php

namespace App\Presenters\PanelType;

use App\Services\Site\HeadingClassesGenerator\BaseGenerator;

class QuotePresenter extends BasePanelPresenter
{
    /**
     * The design shows it using the same colour as the headings so we'll
     * reuse the heading generator
     */
    public function quoteCssColourClass(): string
    {
        $area = $this->getPanel()->area;
        if ($area) {
            $generator = BaseGenerator::buildGenerator($area->background_colour, 'h3');
            return $generator->call();
        }
        return "";
    }
}

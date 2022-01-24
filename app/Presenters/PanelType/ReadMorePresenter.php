<?php

namespace App\Presenters\PanelType;

use App\Services\Site\HeadingClassesGenerator\BaseGenerator;

class ReadMorePresenter extends BasePanelPresenter
{
    public function getReadMoreContent(): string
    {
        return $this->getPanel()->read_more_content;
    }

    /**
     * The design shows it using the same colour as the headings so we'll
     * reuse the heading generator
     */
    public function toggleButtonCssClasses(): string
    {
        $classes = ['toggle-button', 'block', 'font-heading', 'font-bold', 'my-2'];

        $area = $this->getPanel()->area;
        if ($area) {
            $generator = BaseGenerator::buildGenerator($area->background_colour, 'h2');
            $classes[] = $generator->call();
        }

        return implode(' ', $classes);
    }
}

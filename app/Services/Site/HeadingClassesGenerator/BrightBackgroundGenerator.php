<?php

namespace App\Services\Site\HeadingClassesGenerator;

class BrightBackgroundGenerator extends BaseGenerator
{
    public function call(): string
    {
        $classes = [];

        $classes[] = $this->textCssClass();

        return implode(' ', $classes);
    }

    private function textCssClass(): string
    {
        if ($this->getHeadingType() == 'h3') {
            return 'text-web-orange';
        }

        return 'text-white';
    }
}

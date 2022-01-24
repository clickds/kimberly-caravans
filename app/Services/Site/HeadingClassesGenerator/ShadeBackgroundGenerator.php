<?php

namespace App\Services\Site\HeadingClassesGenerator;

class ShadeBackgroundGenerator extends BaseGenerator
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
            return 'text-endeavour';
        }

        return 'text-regal-blue';
    }
}

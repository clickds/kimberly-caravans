<?php

namespace App\Services\Site\HeadingClassesGenerator;

class BlackHeadingGenerator extends BaseGenerator
{
    public function call(): string
    {
        $classes = [];

        $classes[] = $this->textCssClass();

        return implode(' ', $classes);
    }

    private function textCssClass(): string
    {
        return 'text-black';
    }
}

<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use App\Services\Site\HeadingClassesGenerator\BaseGenerator as HeadingClassGenerator;

class AreaPresenter extends BasePresenter
{
    public function cssClasses(): string
    {
        $classes = [
            "py-4",
            "w-full",
            "area",
            "bg-{$this->wrappedObject->background_colour}",
        ];

        return implode(" ", $classes);
    }

    public function innerContainerCssClasses(): string
    {
        $classes = [];
        if ($this->wrappedObject->isStandardWidth()) {
            $classes[] = "container mx-auto px-standard";
        }

        return implode(" ", $classes);
    }

    public function gridCssClasses(): string
    {
        $classes = [
            "grid",
            "grid-cols-1",
            "lg:grid-cols-{$this->wrappedObject->columns}",
            "gap-6",
            "lg:gap-10",
        ];

        return implode(" ", $classes);
    }

    public function getHeading(): string
    {
        $area = $this->getWrappedObject();
        return $area->heading ?: '';
    }

    public function getHeadingType(): string
    {
        return $this->getWrappedObject()->heading_type;
    }

    public function headingCssClasses(): string
    {
        $area = $this->getWrappedObject();

        $headingClassGenerator = HeadingClassGenerator::buildGenerator($area->background_colour, $area->heading_type);
        $classString = $headingClassGenerator->call();

        return $area->heading_text_alignment . ' ' . $classString;
    }
}

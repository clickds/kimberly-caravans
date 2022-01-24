<?php

namespace App\Services\Site\HeadingClassesGenerator;

use App\Models\Interfaces\HasThemedProperties;
use Exception;

abstract class BaseGenerator
{
    private string $headingType;

    public function __construct(string $headingType)
    {
        $this->headingType = $headingType;
    }

    abstract public function call(): string;

    protected function getHeadingType(): string
    {
        return $this->headingType;
    }

    public static function buildGenerator(string $backgroundColour, string $headingType): BaseGenerator
    {
        switch ($backgroundColour) {
            case HasThemedProperties::ENDEAVOUR:
            case HasThemedProperties::SHIRAZ:
                return new BrightBackgroundGenerator($headingType);
            case HasThemedProperties::ALABASTER:
            case HasThemedProperties::GALLERY:
            case HasThemedProperties::WHITE:
                return new ShadeBackgroundGenerator($headingType);
            case HasThemedProperties::WEB_ORANGE:
                return new WebOrangeBackgroundGenerator($headingType);
            case HasThemedProperties::BENIMAR_TESSORO_GREEN:
            case HasThemedProperties::BENIMAR_BENIVAN_ORANGE:
            case HasThemedProperties::MOBILVETTA_RED:
            case HasThemedProperties::MAJESTIC_GREY:
            case HasThemedProperties::MARQUIS_BLUE:
            case HasThemedProperties::MARQUIS_GOLD:
            case HasThemedProperties::MARQUIS_DARK_GREY:
            case HasThemedProperties::SERVICE_BLUE:
            case HasThemedProperties::AUTUMN_BROWN:
            case HasThemedProperties::WINTER_BLUE:
            case HasThemedProperties::BLACK:
            case HasThemedProperties::MONARCH:
            case HasThemedProperties::REGAL_BLUE:
                return new WhiteHeadingGenerator($headingType);
            case HasThemedProperties::BENIMAR_MILEO_YELLOW:
            case HasThemedProperties::BENIMAR_PRIMERO_PURPLE:
            case HasThemedProperties::SPRING_GREEN:
            case HasThemedProperties::SUMMER_YELLOW:
            case HasThemedProperties::JANUARY_SALE_PINK:
            case HasThemedProperties::TRANSPARENT:
                return new BlackHeadingGenerator($headingType);
            default:
                throw new Exception('Missing heading class generator for ' . $backgroundColour);
        }
    }
}

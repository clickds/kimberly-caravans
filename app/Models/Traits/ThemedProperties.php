<?php

namespace App\Models\Traits;

use App\Models\Interfaces\HasThemedProperties;
use InvalidArgumentException;

trait ThemedProperties
{
    public function getBackgroundColourClass(string $propertyName): string
    {
        switch ($propertyName) {
            case HasThemedProperties::BENIMAR_MILEO_YELLOW:
                return 'bg-benimar-mileo-yellow';
            case HasThemedProperties::BENIMAR_TESSORO_GREEN:
                return 'bg-benimar-tessoro-green';
            case HasThemedProperties::BENIMAR_PRIMERO_PURPLE:
                return 'bg-benimar-primero-purple';
            case HasThemedProperties::BENIMAR_BENIVAN_ORANGE:
                return 'bg-benimar-benivan-orange';
            case HasThemedProperties::MOBILVETTA_RED:
                return 'bg-mobilvetta-red';
            case HasThemedProperties::MAJESTIC_GREY:
                return 'bg-majestic-grey';
            case HasThemedProperties::MARQUIS_BLUE:
                return 'bg-marquis-blue';
            case HasThemedProperties::MARQUIS_GOLD:
                return 'bg-marquis-gold';
            case HasThemedProperties::MARQUIS_DARK_GREY:
                return 'bg-marquis-dark-grey';
            case HasThemedProperties::SHIRAZ:
                return 'bg-shiraz';
            case HasThemedProperties::MONARCH:
                return 'bg-monarch';
            case HasThemedProperties::WEB_ORANGE:
                return 'bg-web-orange';
            case HasThemedProperties::SERVICE_BLUE:
                return 'bg-service-blue';
            case HasThemedProperties::SPRING_GREEN:
                return 'bg-spring-green';
            case HasThemedProperties::SUMMER_YELLOW:
                return 'bg-summer-yellow';
            case HasThemedProperties::AUTUMN_BROWN:
                return 'bg-autumn-brown';
            case HasThemedProperties::WINTER_BLUE:
                return 'bg-winter-blue';
            case HasThemedProperties::BLACK:
                return 'bg-black';
            case HasThemedProperties::JANUARY_SALE_PINK:
                return 'bg-january-sale-pink';
            case HasThemedProperties::ALABASTER:
                return 'bg-alabaster';
            case HasThemedProperties::GALLERY:
                return 'bg-gallery';
            case HasThemedProperties::WHITE:
                return 'bg-white';
            case HasThemedProperties::ENDEAVOUR:
                return 'bg-endeavour';
            case HasThemedProperties::REGAL_BLUE:
                return 'bg-regal-blue';
            case HasThemedProperties::TRANSPARENT:
                return 'bg-transparent';
            default:
                throw new InvalidArgumentException('Invalid theme property');
        }
    }

    public function getTextColourClass(string $propertyName): string
    {
        switch ($propertyName) {
            case HasThemedProperties::BENIMAR_MILEO_YELLOW:
                return 'text-benimar-mileo-yellow';
            case HasThemedProperties::BENIMAR_TESSORO_GREEN:
                return 'text-benimar-tessoro-green';
            case HasThemedProperties::BENIMAR_PRIMERO_PURPLE:
                return 'text-benimar-primero-purple';
            case HasThemedProperties::BENIMAR_BENIVAN_ORANGE:
                return 'text-benimar-benivan-orange';
            case HasThemedProperties::MOBILVETTA_RED:
                return 'text-mobilvetta-red';
            case HasThemedProperties::MAJESTIC_GREY:
                return 'text-majestic-grey';
            case HasThemedProperties::MARQUIS_BLUE:
                return 'text-marquis-blue';
            case HasThemedProperties::MARQUIS_GOLD:
                return 'text-marquis-gold';
            case HasThemedProperties::MARQUIS_DARK_GREY:
                return 'text-marquis-dark-grey';
            case HasThemedProperties::SHIRAZ:
                return 'text-shiraz';
            case HasThemedProperties::MONARCH:
                return 'text-monarch';
            case HasThemedProperties::WEB_ORANGE:
                return 'text-web-orange';
            case HasThemedProperties::SERVICE_BLUE:
                return 'text-service-blue';
            case HasThemedProperties::SPRING_GREEN:
                return 'text-spring-green';
            case HasThemedProperties::SUMMER_YELLOW:
                return 'text-summer-yellow';
            case HasThemedProperties::AUTUMN_BROWN:
                return 'text-autumn-brown';
            case HasThemedProperties::WINTER_BLUE:
                return 'text-winter-blue';
            case HasThemedProperties::BLACK:
                return 'text-black';
            case HasThemedProperties::JANUARY_SALE_PINK:
                return 'text-january-sale-pink';
            case HasThemedProperties::ALABASTER:
                return 'text-alabaster';
            case HasThemedProperties::GALLERY:
                return 'text-gallery';
            case HasThemedProperties::WHITE:
                return 'text-white';
            case HasThemedProperties::ENDEAVOUR:
                return 'text-endeavour';
            case HasThemedProperties::REGAL_BLUE:
                return 'text-regal-blue';
            case HasThemedProperties::TRANSPARENT:
                return 'text-transparent';
            default:
                throw new InvalidArgumentException('Invalid theme property');
        }
    }

    public function getBorderColourClass(string $propertyName): string
    {
        switch ($propertyName) {
            case HasThemedProperties::BENIMAR_MILEO_YELLOW:
                return 'border-benimar-mileo-yellow';
            case HasThemedProperties::BENIMAR_TESSORO_GREEN:
                return 'border-benimar-tessoro-green';
            case HasThemedProperties::BENIMAR_PRIMERO_PURPLE:
                return 'border-benimar-primero-purple';
            case HasThemedProperties::BENIMAR_BENIVAN_ORANGE:
                return 'border-benimar-benivan-orange';
            case HasThemedProperties::MOBILVETTA_RED:
                return 'border-mobilvetta-red';
            case HasThemedProperties::MAJESTIC_GREY:
                return 'border-majestic-grey';
            case HasThemedProperties::MARQUIS_BLUE:
                return 'border-marquis-blue';
            case HasThemedProperties::MARQUIS_GOLD:
                return 'border-marquis-gold';
            case HasThemedProperties::MARQUIS_DARK_GREY:
                return 'border-marquis-dark-grey';
            case HasThemedProperties::SHIRAZ:
                return 'border-shiraz';
            case HasThemedProperties::MONARCH:
                return 'border-monarch';
            case HasThemedProperties::WEB_ORANGE:
                return 'border-web-orange';
            case HasThemedProperties::SERVICE_BLUE:
                return 'border-service-blue';
            case HasThemedProperties::SPRING_GREEN:
                return 'border-spring-green';
            case HasThemedProperties::SUMMER_YELLOW:
                return 'border-summer-yellow';
            case HasThemedProperties::AUTUMN_BROWN:
                return 'border-autumn-brown';
            case HasThemedProperties::WINTER_BLUE:
                return 'border-winter-blue';
            case HasThemedProperties::BLACK:
                return 'border-black';
            case HasThemedProperties::JANUARY_SALE_PINK:
                return 'border-january-sale-pink';
            case HasThemedProperties::ALABASTER:
                return 'border-alabaster';
            case HasThemedProperties::GALLERY:
                return 'border-gallery';
            case HasThemedProperties::WHITE:
                return 'border-white';
            case HasThemedProperties::ENDEAVOUR:
                return 'border-endeavour';
            case HasThemedProperties::REGAL_BLUE:
                return 'border-regal-blue';
            case HasThemedProperties::TRANSPARENT:
                return 'border-transparent';
            default:
                throw new InvalidArgumentException('Invalid theme property');
        }
    }
}

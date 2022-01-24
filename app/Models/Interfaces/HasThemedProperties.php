<?php

namespace App\Models\Interfaces;

interface HasThemedProperties
{
    public const BENIMAR_MILEO_YELLOW = 'benimar-mileo-yellow';
    public const BENIMAR_TESSORO_GREEN = 'benimar-tessoro-green';
    public const BENIMAR_PRIMERO_PURPLE = 'benimar-primero-purple';
    public const BENIMAR_BENIVAN_ORANGE = 'benimar-benivan-orange';
    public const MOBILVETTA_RED = 'mobilvetta-red';
    public const MAJESTIC_GREY = 'majestic-grey';
    public const MARQUIS_BLUE = 'marquis-blue';
    public const MARQUIS_GOLD = 'marquis-gold';
    public const MARQUIS_DARK_GREY = 'marquis-dark-grey';
    public const SHIRAZ = 'shiraz';
    public const MONARCH = 'monarch';
    public const WEB_ORANGE = 'web-orange';
    public const SERVICE_BLUE = 'service-blue';
    public const SPRING_GREEN = 'spring-green';
    public const SUMMER_YELLOW = 'summer-yellow';
    public const AUTUMN_BROWN = 'autumn-brown';
    public const WINTER_BLUE = 'winter-blue';
    public const BLACK = 'black';
    public const JANUARY_SALE_PINK = 'january-sale-pink';
    public const ALABASTER = 'alabaster';
    public const GALLERY = 'gallery';
    public const WHITE = 'white';
    public const ENDEAVOUR = 'endeavour';
    public const REGAL_BLUE = 'regal-blue';
    public const TRANSPARENT = 'transparent';

    public const COLOURS = [
        self::BENIMAR_MILEO_YELLOW => 'Benimar Mileo Yellow',
        self::BENIMAR_TESSORO_GREEN => 'Benimar Tessoro Green',
        self::BENIMAR_PRIMERO_PURPLE => 'Benimar Primero Purple',
        self::BENIMAR_BENIVAN_ORANGE => 'Benimar Benivan Orange',
        self::MOBILVETTA_RED => 'Mobilvetta Red',
        self::MAJESTIC_GREY => 'Majestic Grey',
        self::MARQUIS_BLUE => 'Marquis Blue',
        self::MARQUIS_GOLD => 'Marquis Gold',
        self::MARQUIS_DARK_GREY => 'Marquis Dark Grey',
        self::SHIRAZ => 'Marquis Red',
        self::MONARCH => 'Marquis Dark Red',
        self::WEB_ORANGE => 'Marquis Yellow',
        self::SERVICE_BLUE => 'Service Blue',
        self::SPRING_GREEN => 'Spring Green',
        self::SUMMER_YELLOW => 'Summer Yellow',
        self::AUTUMN_BROWN => 'Autumn Brown',
        self::WINTER_BLUE => 'Winter Blue',
        self::BLACK => 'Black',
        self::JANUARY_SALE_PINK => 'January Sale Pink',
        self::ALABASTER => 'Light Grey',
        self::GALLERY => 'Grey',
        self::WHITE => 'White',
        self::ENDEAVOUR => 'Sky Blue',
        self::REGAL_BLUE => 'Dark Blue',
        self::TRANSPARENT => 'None',
    ];

    public function getBackgroundColourClass(string $propertyName): string;

    public function getTextColourClass(string $propertyName): string;
}

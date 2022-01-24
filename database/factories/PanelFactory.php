<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Area;
use App\Models\Form;
use App\Models\Panel;
use App\Models\Video;
use Faker\Generator as Faker;

$factory->define(Panel::class, function (Faker $faker) {
    return [
        'area_id' => function () {
            return factory(Area::class)->create()->id;
        },
        'name' => $faker->name,
        'heading' => $faker->name,
        'heading_type' => $faker->randomElement(Panel::HEADING_TYPES),
        'type' => $faker->randomElement(array_keys(Panel::TYPES)),
        'vertical_positioning' => $faker->randomElement(array_keys(Panel::VERTICAL_POSITIONS)),
        'content' => $faker->paragraphs(rand(1, 6), true),
        'read_more_content' => $faker->paragraphs(rand(1, 6), true),
        'live' => true,
        'published_at' => null,
        'expired_at' => null,
    ];
});

$factory->state(Panel::class, 'standard-type', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_STANDARD,
    ];
});


$factory->state(Panel::class, 'featured-image', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_FEATURED_IMAGE,
    ];
});

$factory->state(Panel::class, 'image', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_IMAGE,
    ];
});

$factory->state(Panel::class, 'manufacturer-slider', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_MANUFACTURER_SLIDER,
    ];
});

$factory->state(Panel::class, 'quote', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_QUOTE,
    ];
});

$factory->state(Panel::class, 'read-more', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_READ_MORE,
    ];
});

$factory->state(Panel::class, 'search-by-berth', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_SEARCH_BY_BERTH,
    ];
});

$factory->state(Panel::class, 'stock-item-category-tabs', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_STOCK_ITEM_CATEGORY_TABS,
    ];
});

$factory->state(Panel::class, 'special-offers', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_SPECIAL_OFFERS,
    ];
});

$factory->state(Panel::class, 'form', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_FORM,
        'featureable_type' => Form::class,
        'featureable_id' => function () {
            return factory(Form::class)->create()->id;
        },
    ];
});

$factory->state(Panel::class, 'video', function (Faker $faker) {
    return [
        'type' => Panel::TYPE_VIDEO,
        'featureable_type' => Video::class,
        'featureable_id' => function () {
            return factory(Video::class)->create()->id;
        },
    ];
});

$factory->state(Panel::class, 'position-top', function () {
    return [
        'vertical_positioning' => Panel::POSITION_TOP,
    ];
});

$factory->state(Panel::class, 'position-middle', function () {
    return [
        'vertical_positioning' => Panel::POSITION_MIDDLE,
    ];
});
$factory->state(Panel::class, 'position-bottom', function () {
    return [
        'vertical_positioning' => Panel::POSITION_BOTTOM,
    ];
});

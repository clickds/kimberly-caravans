<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Area;
use App\Models\Page;
use Faker\Generator as Faker;

$factory->define(Area::class, function (Faker $faker) {
    return [
        'background_colour' => array_rand(Area::BACKGROUND_COLOURS),
        'columns' => $faker->randomElement(Area::COLUMNS),
        'heading' => $faker->text('10'),
        'holder' => 'Primary',
        'page_id' => function () {
            return factory(Page::class)->create()->id;
        },
        'name' => $faker->name,
        'width' => array_rand(Area::WIDTHS),
        'live' => true,
        'published_at' => null,
        'expired_at' => null,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\NavigationItem;
use App\Models\Navigation;
use Faker\Generator as Faker;

$factory->define(NavigationItem::class, function (Faker $faker) {
    return [
        'navigation_id' => 1,
        'parent_id' => null,
        'name' => $faker->word,
        'page_id' => null,
        'query_parameters' => null,
        'external_url' => $faker->url,
        'background_colour' => array_keys(NavigationItem::BACKGROUND_COLOURS)[0],
    ];
});

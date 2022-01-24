<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Navigation;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(Navigation::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'site_id' => function () {
            return Site::all()->random()->id;
        },
        'type' => $faker->randomElement(Navigation::NAVIGATION_TYPES),
    ];
});

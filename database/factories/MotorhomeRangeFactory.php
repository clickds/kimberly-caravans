<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MotorhomeRange;
use App\Models\Manufacturer;
use Faker\Generator as Faker;

$factory->define(MotorhomeRange::class, function (Faker $faker) {
    return [
        'manufacturer_id' => function () {
            return factory(Manufacturer::class)->create()->id;
        },
        'name' => $faker->unique()->company,
        'prepend_range_name_to_model_names' => $faker->boolean(),
        'overview' => $faker->sentence(rand(7, 50)),
        'position' => $faker->randomDigit,
        'primary_theme_colour' => $faker->randomElement(array_keys(MotorhomeRange::PRIMARY_THEME_COLOURS)),
        'secondary_theme_colour' => $faker->randomElement(array_keys(MotorhomeRange::SECONDARY_THEME_COLOURS)),
        'live' => true,
        'exclusive' => false,
    ];
});

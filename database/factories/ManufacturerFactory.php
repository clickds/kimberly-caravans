<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Manufacturer;
use Faker\Generator as Faker;

$factory->define(Manufacturer::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'motorhome_position' => $faker->randomDigit,
        'caravan_position' => $faker->randomDigit,
        'exclusive' => false,
        'link_directly_to_stock_search' => false,
    ];
});

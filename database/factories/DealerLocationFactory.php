<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DealerLocation;
use Faker\Generator as Faker;

$factory->define(DealerLocation::class, function (Faker $faker) {
    return [
        'line_1' => $faker->streetName,
        'line_2' => null,
        'town' => $faker->city,
        'county' => $faker->state,
        'postcode' => $faker->postcode,
        'phone' => $faker->phoneNumber,
        'fax' => $faker->phoneNumber,
        'sat_nav_code' => null,
        'opening_hours' => $faker->paragraph(),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'google_maps_url' => $faker->url,
    ];
});

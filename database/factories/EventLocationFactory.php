<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EventLocation;
use Faker\Generator as Faker;

$factory->define(EventLocation::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});

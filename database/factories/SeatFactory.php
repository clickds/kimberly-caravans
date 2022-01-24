<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Seat;
use Faker\Generator as Faker;

$factory->define(Seat::class, function (Faker $faker) {
    return [
        'number' => $faker->unique()->randomDigitNotNull,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Berth;
use Faker\Generator as Faker;

$factory->define(Berth::class, function (Faker $faker) {
    return [
        'number' => $faker->unique()->randomDigitNotNull,
    ];
});

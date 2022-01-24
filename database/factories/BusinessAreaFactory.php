<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BusinessArea;
use Faker\Generator as Faker;

$factory->define(BusinessArea::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'email' => $faker->unique()->email,
    ];
});

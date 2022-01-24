<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Layout;
use Faker\Generator as Faker;

$factory->define(Layout::class, function (Faker $faker) {
    return [
        'code' => strtoupper($faker->unique()->word()),
        'name' => $faker->sentence(),
    ];
});
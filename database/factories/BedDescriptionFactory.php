<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BedDescription;
use Faker\Generator as Faker;

$factory->define(BedDescription::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});

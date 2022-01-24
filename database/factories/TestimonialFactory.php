<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Testimonial;
use Faker\Generator as Faker;

$factory->define(Testimonial::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'content' => $faker->paragraph(),
        'position' => 0,
    ];
});

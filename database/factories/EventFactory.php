<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'start_date' => $faker->date(),
        'end_date' => $faker->date(),
        'summary' => $faker->paragraph(),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Motorhome;
use App\Models\OptionalWeight;
use Faker\Generator as Faker;

$factory->define(OptionalWeight::class, function (Faker $faker) {
    return [
        'motorhome_id' => function () {
            return factory(Motorhome::class)->create()->id;
        },
        'name' => $faker->name(),
        'value' => $faker->paragraph(),
    ];
});

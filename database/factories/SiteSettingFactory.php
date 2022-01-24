<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SiteSetting;
use Faker\Generator as Faker;

$factory->define(SiteSetting::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(SiteSetting::VALID_SETTINGS),
        'description' => $faker->paragraph(1),
        'value' => $faker->randomNumber(),
    ];
});

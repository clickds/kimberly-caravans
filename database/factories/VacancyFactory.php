<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Vacancy;
use Faker\Generator as Faker;

$factory->define(Vacancy::class, function (Faker $faker) {
    return [
        'title' => $faker->jobTitle,
        'salary' => '£10 per hour',
        'short_description' => $faker->randomHtml(),
        'description' => $faker->randomHtml(),
    ];
});

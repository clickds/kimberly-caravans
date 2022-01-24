<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ReviewCategory;
use Faker\Generator as Faker;

$factory->define(ReviewCategory::class, function (Faker $faker) {
    return ['name' => $faker->name];
});

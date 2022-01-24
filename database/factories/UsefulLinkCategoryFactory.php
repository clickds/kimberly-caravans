<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UsefulLinkCategory;
use Faker\Generator as Faker;

$factory->define(UsefulLinkCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'position' => 0,
    ];
});

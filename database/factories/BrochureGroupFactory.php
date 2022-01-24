<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BrochureGroup;
use Faker\Generator as Faker;
use App\Models\Brochure;

$factory->define(BrochureGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});

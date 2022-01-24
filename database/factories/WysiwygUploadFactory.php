<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WysiwygUpload;
use Faker\Generator as Faker;

$factory->define(WysiwygUpload::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});

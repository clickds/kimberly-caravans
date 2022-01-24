<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fieldset;
use App\Models\Form;
use Faker\Generator as Faker;

$factory->define(Fieldset::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'content' => $faker->sentence(),
    ];
});

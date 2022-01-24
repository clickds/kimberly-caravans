<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DealerEmployee;
use Faker\Generator as Faker;

$factory->define(DealerEmployee::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'role' => $faker->jobTitle,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'position' => $faker->randomNumber(1),
    ];
});

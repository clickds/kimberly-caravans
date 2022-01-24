<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\VehicleEnquiry;
use Faker\Generator as Faker;

$factory->define(VehicleEnquiry::class, function (Faker $faker) {
    return [
        'title' => $faker->title(),
        'first_name' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->email,
        'phone_number' => $faker->phoneNumber,
        'county' => $faker->country,
        'message' => $faker->sentence(),
        'help_methods' => ['Something', 'Something else'],
        'interests' => ['caravans', 'motorhomes'],
        'marketing_preferences' => ['email'],
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EmailRecipient;
use Faker\Generator as Faker;

$factory->define(EmailRecipient::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'email' => $faker->unique()->email,
        'receives_vehicle_enquiry' => true,
    ];
});

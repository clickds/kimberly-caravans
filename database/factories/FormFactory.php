<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Form;
use Faker\Generator as Faker;

$factory->define(Form::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence,
        'email_to' => $faker->email,
        'type' => $faker->randomElement(Form::VALID_TYPES),
        'successful_submission_message' => 'Thanks for your input',
        'crm_list' => null,
    ];
});

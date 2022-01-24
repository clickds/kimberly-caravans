<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Form;
use App\Models\FormSubmission;
use Faker\Generator as Faker;

$factory->define(FormSubmission::class, function (Faker $faker) {
    return [
        'form_id' => function () {
            return factory(Form::class)->create()->id;
        },
        'submission_data' => [
            'checkbox label' => true,
            'text field label' =>  "some text",
            'radio button label' => "a",
            'email field label' => "test@example.com",
            'select field label' => "a",
            'text area label' => "text area content",
            'multiple checkboxes label' => ["a", "b", "c"]
        ],
    ];
});

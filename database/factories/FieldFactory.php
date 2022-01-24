<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Field;
use App\Models\Fieldset;
use Faker\Generator as Faker;

$factory->define(Field::class, function (Faker $faker) {
    $label = $faker->unique()->word;
    return [
        'fieldset_id' => function () {
            return factory(Fieldset::class)->create()->id;
        },
        'input_name' => $label,
        'label' => $label,
        'crm_field_name' => null,
        'name' => $faker->word,
        'options' => null,
        'position' => 0,
        'required' => true,
        'type' => $faker->randomElement(array_keys(Field::TYPES)),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cta;
use App\Models\Page;
use Faker\Generator as Faker;

$factory->define(Cta::class, function (Faker $faker) {
    $page = factory(Page::class)->create();

    return [
        'title' => $faker->sentence($nbWords = rand(2, 9), $variableNbWords = true),
        'excerpt_text' => $faker->text,
        'site_id' => $page->site_id,
        'link_text' => 'abc',
        'page_id' => $page->id,
        'type' => $faker->randomElement(Cta::TYPES),
        'position' => 0,
    ];
});

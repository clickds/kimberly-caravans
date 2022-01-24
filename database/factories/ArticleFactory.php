<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use App\Models\Dealer;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'excerpt' => $faker->paragraph(),
        'date' => $faker->dateTime(),
        'dealer_id' => function () {
            return factory(Dealer::class)->create()->id;
        },
        'type' => $faker->randomElement(Article::TYPES),
        'style' => $faker->randomElement(Article::STYLES),
        'live' => $faker->boolean(),
        'expired_at' => null,
    ];
});

$factory->state(Article::class, 'news', [
    'style' => Article::STYLE_NEWS,
]);
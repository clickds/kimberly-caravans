<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Video;
use App\Models\VideoCategory;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'type' => $faker->randomElement(Video::VALID_TYPES),
        'excerpt' => $faker->paragraph(),
        'published_at' => $faker->dateTime(),
        'embed_code' => <<<'EOT'
        <iframe width="560" height="315" src="https://www.youtube.com/embed/bEeaS6fuUoA"
            frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
        EOT,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\MotorhomeRange;
use App\Models\CaravanRange;
use App\Models\WysiwygUpload;
use Faker\Generator as Faker;

$factory->define(Media::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'model_id' => null,
        'model_type' => null,
        'collection_name' => 'gallery',
        'file_name' => 'avatar.jpg',
        'disk' => 'public',
        'size' => 1024,
        'manipulations' => [],
        'custom_properties' => [],
        'responsive_images' => [],
    ];
});

$factory->state(Media::class, 'wysiwyg-upload', function (Faker $faker) {
    $upload = factory(WysiwygUpload::class)->create();

    return [
        'model_id' => $upload->id,
        'model_type' => WysiwygUpload::class,
    ];
});

$factory->state(Media::class, 'motorhome-range', function (Faker $faker) {
    $motorhomeRange = factory(MotorhomeRange::class)->create();

    return [
        'model_id' => $motorhomeRange->id,
        'model_type' => MotorhomeRange::class,
    ];
});

$factory->state(Media::class, 'caravan-range', function (Faker $faker) {
    $caravanRange = factory(CaravanRange::class)->create();

    return [
        'model_id' => $caravanRange->id,
        'model_type' => CaravanRange::class,
    ];
});

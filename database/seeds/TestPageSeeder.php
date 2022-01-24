<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Area;
use App\Models\Form;
use App\Models\Page;
use App\Models\Panel;
use App\Models\Site;
use App\Models\SpecialOffer;
use App\Models\Video;

class TestPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $site = Site::firstOrCreate(
            [
                'is_default' => true
            ],
            [
                'country' => 'England',
                'subdomain' => 'www',
            ]
        );

        $page = factory(Page::class)->state('standard-template')->create([
            'site_id' => $site->id,
            'name' => 'Test',
        ]);

        $twoColumnArea = factory(Area::class)->create([
            'page_id' => $page->id,
            'columns' => 2,
            'background_colour' => $faker->randomElement(array_keys(Area::BACKGROUND_COLOURS)),
        ]);
        for ($i = 1; $i < 4; $i++) {
            $states = [
                'standard-type',
            ];

            switch ($i % 3) {
                case 0:
                    $states[] = "position-bottom";
                    break;
                case 1:
                    $states[] = "position-middle";
                    break;
                case 2:
                    $states[] = "position-top";
                    break;
            }

            factory(Panel::class)->states($states)->create([
                'area_id' => $twoColumnArea->id,
                'content' => $faker->paragraph(),
            ]);

            factory(Panel::class)->states($states)->create([
                'area_id' => $twoColumnArea->id,
                'content' => $faker->paragraphs(4, true),
            ]);
        }

        $singleColumnArea = factory(Area::class)->create([
            'page_id' => $page->id,
            'columns' => 1,
        ]);

        factory(Panel::class)->state('standard-type')->create([
            'area_id' => $singleColumnArea->id,
        ]);

        $form = factory(Form::class)->create();
        // TODO: Make form have fieldsets and fields
        factory(Panel::class)->state('form')->create([
            'featureable_type' => Form::class,
            'featureable_id' => $form->id,
            'area_id' => $singleColumnArea->id,
        ]);

        // Will create the video
        factory(Panel::class)->state('video')->create([
            'area_id' => $singleColumnArea->id,
        ]);

        $supportImagePath = base_path() . '/tests/Support/Files/';

        $featuredImagePanel = factory(Panel::class)->state('featured-image')->create([
            'area_id' => $singleColumnArea->id,
        ]);
        $filePath = $supportImagePath . '1920x480.jpg';
        if (file_exists($filePath)) {
            $featuredImagePanel->addMedia($filePath)->preservingOriginal()->toMediaCollection('featuredImage');
        }

        $imagePanel = factory(Panel::class)->state('image')->create([
            'area_id' => $singleColumnArea->id,
        ]);
        $filePath = $supportImagePath . 'test.jpg';
        if (file_exists($filePath)) {
            $imagePanel->addMedia($filePath)->preservingOriginal()->toMediaCollection('image');
        }

        factory(Panel::class)->state('quote')->create([
            'area_id' => $singleColumnArea->id,
        ]);

        factory(Panel::class)->state('read-more')->create([
            'area_id' => $singleColumnArea->id,
        ]);

        $specialOffers = factory(SpecialOffer::class, 10)->create();
        foreach ($specialOffers as $specialOffer) {
            $squareImageFile = $supportImagePath . 'test.jpg';
            if (file_exists($squareImageFile)) {
                $specialOffer->addMedia($squareImageFile)->preservingOriginal()->toMediaCollection('squareImage');
            }
            $landscapeImageFile = $supportImagePath . '1920x480.jpg';
            if (file_exists($landscapeImageFile)) {
                $specialOffer->addMedia($squareImageFile)->preservingOriginal()->toMediaCollection('landscapeImage');
            }
        }
        $specialOfferPanel = factory(Panel::class)->state('special-offers')->create([
            'area_id' => $singleColumnArea->id,
        ]);
        $specialOfferPanel->specialOffers()->attach($specialOffers);

        foreach (Panel::VEHICLE_TYPES as $vehicleType) {
            factory(Panel::class)->state('manufacturer-slider')->create([
                'area_id' => $singleColumnArea->id,
                'vehicle_type' => $vehicleType,
            ]);
        }

        foreach (Panel::VEHICLE_TYPES as $vehicleType) {
            factory(Panel::class)->state('search-by-berth')->create([
                'area_id' => $singleColumnArea->id,
                'vehicle_type' => $vehicleType,
            ]);
        }

        foreach (Panel::VEHICLE_TYPES as $vehicleType) {
            factory(Panel::class)->state('stock-item-category-tabs')->create([
                'area_id' => $singleColumnArea->id,
                'vehicle_type' => $vehicleType,
            ]);
        }

        $url = route('site', ['page' => $page->slug]);
        $this->command->getOutput()->writeln("<info>Page url is:</info>  {$url}");
    }
}

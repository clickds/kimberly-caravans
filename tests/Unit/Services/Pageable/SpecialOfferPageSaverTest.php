<?php

namespace Tests\Unit\Services\Pageable;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SpecialOffer;
use App\Models\Page;
use App\Services\Pageable\SpecialOfferPageSaver;

class SpecialOfferPageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_special_offer_pageable_has_type_of_both_creates_special_offer_show_page()
    {
        $site = $this->createSite();
        $specialOffer = factory(SpecialOffer::class)->create([
            'name' => 'my-offer',
            'type' => SpecialOffer::TYPE_BOTH,
        ]);
        $saver = new SpecialOfferPageSaver($specialOffer, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $specialOffer->name,
            "pageable_type" => SpecialOffer::class,
            "pageable_id" => $specialOffer->id,
            "template" => Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
            "slug" => "my-offer-caravan",
        ]);
        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $specialOffer->name,
            "pageable_type" => SpecialOffer::class,
            "pageable_id" => $specialOffer->id,
            "template" => Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
            "slug" => "my-offer-motorhome",
        ]);
    }

    public function test_deletes_unneeded_pages_when_type_becomes_caravan(): void
    {
        $site = $this->createSite();
        $specialOffer = factory(SpecialOffer::class)->create([
            'name' => 'my-offer',
            'type' => SpecialOffer::TYPE_BOTH,
        ]);
        $caravanPage = factory(Page::class)->create([
            'site_id' => $site->id,
            'pageable_type' => SpecialOffer::class,
            'pageable_id' => $specialOffer->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
            "slug" => "my-offer-caravan",
        ]);
        $motorhomePage = factory(Page::class)->create([
            'site_id' => $site->id,
            'pageable_type' => SpecialOffer::class,
            'pageable_id' => $specialOffer->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
            "slug" => "my-offer-motorhome",
        ]);
        $specialOffer->type = SpecialOffer::TYPE_CARAVAN;
        $specialOffer->save();
        $saver = new SpecialOfferPageSaver($specialOffer, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $specialOffer->name,
            "pageable_type" => SpecialOffer::class,
            "pageable_id" => $specialOffer->id,
            "template" => Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
            "slug" => "my-offer-caravan",
        ]);
        $this->assertDatabaseMissing('pages', [
            "site_id" => $site->id,
            "name" => $specialOffer->name,
            "pageable_type" => SpecialOffer::class,
            "pageable_id" => $specialOffer->id,
            "template" => Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
            "slug" => "my-offer-motorhome",
        ]);
    }

    public function test_deletes_unneeded_pages_when_type_becomes_motorhome(): void
    {
        $site = $this->createSite();
        $specialOffer = factory(SpecialOffer::class)->create([
            'name' => 'my-offer',
            'type' => SpecialOffer::TYPE_BOTH,
        ]);
        $caravanPage = factory(Page::class)->create([
            'site_id' => $site->id,
            'pageable_type' => SpecialOffer::class,
            'pageable_id' => $specialOffer->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
            "slug" => "my-offer-caravan",
        ]);
        $motorhomePage = factory(Page::class)->create([
            'site_id' => $site->id,
            'pageable_type' => SpecialOffer::class,
            'pageable_id' => $specialOffer->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
            "slug" => "my-offer-motorhome",
        ]);
        $specialOffer->type = SpecialOffer::TYPE_MOTORHOME;
        $specialOffer->save();
        $saver = new SpecialOfferPageSaver($specialOffer, $site);

        $saver->call();

        $this->assertDatabaseMissing('pages', [
            "site_id" => $site->id,
            "name" => $specialOffer->name,
            "pageable_type" => SpecialOffer::class,
            "pageable_id" => $specialOffer->id,
            "template" => Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
            "slug" => "my-offer-caravan",
        ]);
        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $specialOffer->name,
            "pageable_type" => SpecialOffer::class,
            "pageable_id" => $specialOffer->id,
            "template" => Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
            "slug" => "my-offer-motorhome",
        ]);
    }
}

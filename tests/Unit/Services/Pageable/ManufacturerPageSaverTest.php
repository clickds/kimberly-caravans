<?php

namespace Tests\Unit\Services\Pageable;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Services\Pageable\ManufacturerPageSaver;

class ManufacturerPageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_manufacturer_pageable_creates_manufacturer_motorhomes_page()
    {
        $site = $this->createSite();
        $manufacturer = factory(Manufacturer::class)->create();
        $saver = new ManufacturerPageSaver($manufacturer, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => "{$manufacturer->name} Motorhomes",
            "pageable_type" => Manufacturer::class,
            "pageable_id" => $manufacturer->id,
            "template" => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
        ]);
    }

    public function test_when_manufacturer_pageable_creates_manufacturer_caravans_page()
    {
        $site = $this->createSite();
        $manufacturer = factory(Manufacturer::class)->create();
        $saver = new ManufacturerPageSaver($manufacturer, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => "{$manufacturer->name} Caravans",
            "pageable_type" => Manufacturer::class,
            "pageable_id" => $manufacturer->id,
            "template" => Page::TEMPLATE_MANUFACTURER_CARAVANS,
        ]);
    }
}

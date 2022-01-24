<?php

namespace Tests\Unit\Services\Importers\StockFeed;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use App\Services\Importers\StockFeed\Fetcher;
use App\Services\Importers\StockFeed\MotorhomeCreator;
use App\Models\Layout;
use App\Models\Manufacturer;
use App\Models\SpecialOffer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use App\Events\PageableUpdated;
use App\Models\MotorhomeStockItem;

class MotorhomeCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_links_used_motorhome_stock_link_special_offer(): void
    {
        $specialOffer = factory(SpecialOffer::class)->create([
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => true,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
        ]);

        $feedItem = $this->makeFeedItem(['Condition' => 'Used']);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);

        $this->assertDatabaseHas('motorhome_stock_item_special_offer', [
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_doesnt_link_used_motorhome_stock_link_special_offer(): void
    {
        $specialOffer = factory(SpecialOffer::class)->create([
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => true,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
        ]);

        $feedItem = $this->makeFeedItem(['Condition' => 'New']);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);

        $this->assertDatabaseMissing('motorhome_stock_item_special_offer', [
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_links_on_sale_offer_if_on_sale(): void
    {
        $specialOffer = factory(SpecialOffer::class)->create([
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => true,
        ]);
        $feedItem = $this->makeFeedItem([
            "Price" => "6995", // "Price" actually means "NowPrice"
            "SalePrice" => "7995", // "SalePrice" actually means "WasPrice"
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas('motorhome_stock_item_special_offer', [
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_does_not_link_on_sale_offer_if_not_on_sale(): void
    {
        $specialOffer = factory(SpecialOffer::class)->create([
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
        ]);
        $feedItem = $this->makeFeedItem([
            "Price" => "6995",
            "SalePrice" => "6995",
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseMissing('motorhome_stock_item_special_offer', [
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_links_managers_special_offer(): void
    {
        $specialOffer = factory(SpecialOffer::class)->create([
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => true,
            'link_on_sale_stock' => false,
        ]);
        $feedItem = $this->makeFeedItem([
            "Exclusive" => true,
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas('motorhome_stock_item_special_offer', [
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_creates_a_layout_if_it_does_not_exist()
    {
        $feedItem = $this->makeFeedItem();
        $mockFetcher = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $mockFetcher);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("layouts", [
            "code" => "VS",
            "name" => "Van with shower"
        ]);
    }

    public function test_creates_a_manufacturer_if_it_does_not_exist()
    {
        $feedItem = $this->makeFeedItem();
        $mockFetcher = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $mockFetcher);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("manufacturers", [
            "name" => "Nuventure"
        ]);
    }

    public function test_creates_a_caravan_range_if_it_does_not_exist()
    {
        $feedItem = $this->makeFeedItem();
        $mockFetcher = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $mockFetcher);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("motorhome_ranges", [
            "name" => "Utopia"
        ]);
    }

    public function test_manufacturer_specific_name_fixes(): void
    {
        $feedItem = $this->makeFeedItem([
            'Make' => 'AUTO SLEEPERS',
        ]);
        $mockFetcher = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $mockFetcher);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("manufacturers", [
            "name" => "AUTO-SLEEPERS"
        ]);
    }

    public function test_price_set_correctly_if_price_is_higher_than_the_sale_price()
    {
        $feedItem = $this->makeFeedItem([
            "Price" => "8995", // "Price" actually means "NowPrice"
            "SalePrice" => "7995", // "SalePrice" actually means "WasPrice"
        ]);

        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);

        $this->assertDatabaseHas("motorhome_stock_items", [
            "recommended_price" => 8995,
            "price" => null,
        ]);
    }

    public function test_creates_a_motorhome_stock_item()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Test"
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "TE",
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
        ]);
        $mockFetcher = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $mockFetcher);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("motorhome_stock_items", [
            "managers_special" => true,
            "chassis_manufacturer" => "Renault",
            "condition" => "Used",
            "demonstrator" => false,
            "description" => "2009 Nu Venture Utopia built by a well known small camper company based in Wigan is on the Renault Master chassis. Supplied with the 2.5 Turbo Diesel engine and 120ps, this has the manual 6 speed transmission. This 2 berth version offers two rear opposing side seats and a long side sofa, converting in to two good sized rear single beds. The kitchen provides a full sink with both hot and cold water and a gas hob and microwave. This is complimented by Truma blown air heating. The conversion benefits from a roof solar panel, TV aerial and full sized Omnistor awning.",
            "engine_size" => 2464,
            "fuel" => "Diesel",
            "height" => 2.6,
            "layout_id" => $layout->id,
            "length" => 5.99,
            "manufacturer_id" => $manufacturer->id,
            "mtplm" => 3500,
            "mileage" => 36512,
            "model" => "Utopia",
            "payload" => 650,
            "number_of_owners" => 5,
            "price" => 23995,
            "recommended_price" => 24995,
            "delivery_date" => "2020-01-01",
            "reduced_price_start_date" => "2020-02-02",
            "registration_date" => "2009-03-02",
            "registration_number" => "MV09 XDL",
            "transmission" => "Manual",
            "conversion" => "Camper Van",
            "unique_code" => "44556",
            "mro" => 2850,
            "width" => 2.05,
            "year" => 2009,
        ]);
        $this->assertDatabaseHas('berths', [
            'number' => 2,
        ]);
        $this->assertDatabaseHas('seats', [
            'number' => 2,
        ]);
    }

    public function test_adds_video(): void
    {
        $this->fakeStorage();
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Swift",
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "EB",
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
            "WebVideoURL" => "https://www.youtube.com/watch?v=taOL5HJdx1A",
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $motorhomeStockItem = MotorhomeStockItem::latest()->first();
        $this->assertDatabaseHas('feed_stock_item_videos', [
            'videoable_type' => MotorhomeStockItem::class,
            'videoable_id' => $motorhomeStockItem->id,
            'youtube_url' => 'https://www.youtube.com/watch?v=taOL5HJdx1A',
        ]);
    }


    public function test_creates_features()
    {
        $features = [
            "3 WAY FRIDGE",
            "ALKO STABILISER",
            "ALLOY WHEELS",
            "GAS / ELECTRIC HEATING",
            "HOB / OVEN / GRILL",
            "HOT WATER",
            "MICROWAVE",
            "MOTOR MOVER",
            "SPARE WHEEL & CARRIER",
            "TV AERIAL"
        ];
        $feedItem = $this->makeFeedItem([
            "VehicleFeatures" => $features,
        ]);
        $mockFetcher = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $mockFetcher);

        $result = $importer->call();

        $this->assertTrue($result);
        foreach ($features as $feature) {
            $this->assertDatabaseHas('motorhome_stock_item_features', [
                'name' => Str::title($feature),
            ]);
        }
    }

    public function test_images_are_stored()
    {
        $testFile = $this->getTestJpg();
        $testBase64Data = base64_encode(file_get_contents($testFile));
        $image_id = "100000_1_000001";
        $images = [
            $image_id,
        ];
        $feedItem = $this->makeFeedItem([
            "Images" => $images,
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $fetcherMock->allows()->getImage($image_id)->andReturns([
            'mime_type' => 'image/jpeg',
            'data' => $testBase64Data,
        ]);
        $importer = new MotorhomeCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas('media', [
            'name' => $image_id,
            'file_name' => "{$image_id}.jpg"
        ]);
    }
    public function test_pageable_updated_event_is_dispatched()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Test"
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "TE",
        ]);
        Event::fake([
            PageableUpdated::class,
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
        ]);
        $mockFetcher = Mockery::mock(Fetcher::class);
        $importer = new MotorhomeCreator($feedItem, $mockFetcher);

        $result = $importer->call();

        $this->assertTrue($result);

        Event::assertDispatched(PageableUpdated::class, 2); // Once for motorhome stock item, once for motorhome range.
    }


    private function makeFeedItem($overrides = [])
    {
        $defaults = [
            "Category" => "Motorhome",
            "Condition" => "Used",
            "UniqueCode" => "44556",
            "Make" => "NUVENTURE",
            "WebModel" => "Utopia",
            "Model" => "UTOPIA",
            "Year" => "2009",
            "Berths" => "2",
            "Axles" => "2",
            "OverallLength" => "5.99",
            "MTPLM" => "3500",
            "Location" => "NB",
            "Drive" => "RHD",
            "RegYear" => "2009-03-02",
            "RegNo" => "MV09 XDL",
            "Engine" => "2464",
            "ChassisEngineType" => "RENAULT",
            "Owners" => "5",
            "Mileage" => "36512",
            "Price" => "23995", // "Price" actually means "NowPrice"
            "SalePrice" => "24995", // "SalePrice" actually means "WasPrice"
            "DeliveryDate" => "2020-01-01",
            "ReducedPriceDate" => "2020-02-02",
            "LayoutType" => "Van with Shower",
            "LayoutCode" => "VS",
            "Transmission" => "D",
            "EstimatedPayload" => "650",
            "Width" => "2.05",
            "Height" => "2.6",
            "VehicleDescription" => "2009 Nu Venture Utopia built by a well known small camper company based in Wigan is on the Renault Master chassis. Supplied with the 2.5 Turbo Diesel engine and 120ps, this has the manual 6 speed transmission. This 2 berth version offers two rear opposing side seats and a long side sofa, converting in to two good sized rear single beds. The kitchen provides a full sink with both hot and cold water and a gas hob and microwave. This is complimented by Truma blown air heating. The conversion benefits from a roof solar panel, TV aerial and full sized Omnistor awning.",
            "ManAuto" => "M",
            "ConvType" => "HT",
            "Seats" => "2",
            "MIRO" => "2850",
            "NoOwners" => "5",
            "CCFuel" => "D",
            "MOTDate" => "2020-08-14",
            "Exclusive" => true,
            "VehicleFeatures" => [
                "AWNING",
                "BIKE RACK",
                "BLOWN AIR HEATING",
                "CASSETTE TOILET",
                "DRIVERS AIR BAG",
                "HOB",
                "MICROWAVE",
                "SOLAR PANEL",
                "TV AERIAL"
            ],
            "Images" => [],
        ];

        $data = array_merge($defaults, $overrides);
        return $data;
    }
}

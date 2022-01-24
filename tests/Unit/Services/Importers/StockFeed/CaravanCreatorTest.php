<?php

namespace Tests\Unit\Services\Importers\StockFeed;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use App\Services\Importers\StockFeed\Fetcher;
use App\Services\Importers\StockFeed\CaravanCreator;
use App\Models\Caravan;
use App\Models\Layout;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use App\Events\PageableUpdated;
use App\Models\CaravanStockItem;
use App\Models\SpecialOffer;

class CaravanCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_links_used_caravan_stock_link_special_offer(): void
    {
        $specialOffer = factory(SpecialOffer::class)->create([
            'link_used_caravan_stock' => true,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
        ]);

        $feedItem = $this->makeFeedItem(['Condition' => 'Used']);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas('caravan_stock_item_special_offer', [
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_doesnt_link_new_caravan_stock_link_special_offer(): void
    {
        $specialOffer = factory(SpecialOffer::class)->create([
            'link_used_caravan_stock' => true,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
        ]);

        $feedItem = $this->makeFeedItem(['Condition' => 'New']);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseMissing('caravan_stock_item_special_offer', [
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
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas('caravan_stock_item_special_offer', [
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
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseMissing('caravan_stock_item_special_offer', [
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
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas('caravan_stock_item_special_offer', [
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_creates_a_layout_if_it_does_not_exist()
    {
        $feedItem = $this->makeFeedItem();
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("layouts", [
            "code" => "EB",
            "name" => "End Bathroom"
        ]);
    }

    public function test_creates_a_manufacturer_if_it_does_not_exist()
    {
        $feedItem = $this->makeFeedItem();
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("manufacturers", [
            "name" => "Swift"
        ]);
    }

    public function test_creates_a_caravan_range_if_it_does_not_exist()
    {
        $feedItem = $this->makeFeedItem();
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("caravan_ranges", [
            "name" => "Challenger",
        ]);
    }

    public function test_axles_are_set_to_single_if_feed_has_them_set_to_sa()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Swift",
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "EB",
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
            "Axles" => "SA",
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("caravan_stock_items", [
            "axles" => Caravan::AXLE_SINGLE,
        ]);
    }

    public function test_axles_are_set_to_single_if_feed_has_them_set_to_1()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Swift",
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "EB",
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
            "Axles" => "1",
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("caravan_stock_items", [
            "axles" => Caravan::AXLE_SINGLE,
        ]);
    }

    public function test_axles_are_set_to_single_if_feed_has_them_set_to_ta()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Swift",
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "EB",
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
            "Axles" => "TA",
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("caravan_stock_items", [
            "axles" => Caravan::AXLE_TWIN,
        ]);
    }

    public function test_axles_are_set_to_single_if_feed_has_them_set_to_2()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Swift",
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "EB",
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
            "Axles" => "2",
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("caravan_stock_items", [
            "axles" => Caravan::AXLE_TWIN,
        ]);
    }

    public function test_price_set_correctly_if_price_is_higher_than_the_sale_price()
    {
        $feedItem = $this->makeFeedItem([
            "Price" => "8995", // "Price" actually means "NowPrice"
            "SalePrice" => "7995", // "SalePrice" actually means "WasPrice"
        ]);

        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);

        $this->assertDatabaseHas("caravan_stock_items", [
            "recommended_price" => 8995,
            "price" => null,
        ]);
    }

    public function test_creates_a_caravan_stock_item()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            "name" => "Swift",
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "EB",
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $this->assertDatabaseHas("caravan_stock_items", [
            "managers_special" => true,
            "model" => "Challenger 480 SE",
            "manufacturer_id" => $manufacturer->id,
            "demonstrator" => false,
            "layout_id" => $layout->id,
            "condition" => "Used",
            "unique_code" => "44533",
            "year" => 2007,
            "axles" => "Single",
            "registration_date" => "2007-05-25",
            "number_of_owners" => 4,
            "length" => 6.42,
            "width" => 2.23,
            "height" => 2.34,
            "price" => 6995,
            "recommended_price" => 7995,
            "delivery_date" => "2020-01-01",
            "reduced_price_start_date" => "2020-02-02",
            "payload" => 178,
            "mtplm" => 1360,
            "mro" => 1182,
            "description" => "This 2007 Swift Conqueror is built on a single-axle chassis with stabiliser. The 2-berth interior layout consists of a front lounge, central kitchen with storage opposite and a full width end washroom with separate shower compartment.\r\n\r\nStandard specification includes :",
        ]);
        $this->assertDatabaseHas('berths', [
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
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        $caravanStockItem = CaravanStockItem::latest()->first();
        $this->assertDatabaseHas('feed_stock_item_videos', [
            'videoable_type' => CaravanStockItem::class,
            'videoable_id' => $caravanStockItem->id,
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
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);
        foreach ($features as $feature) {
            $this->assertDatabaseHas('caravan_stock_item_features', [
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
        $importer = new CaravanCreator($feedItem, $fetcherMock);

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
            "name" => "Swift",
        ]);
        $layout = factory(Layout::class)->create([
            "code" => "EB",
        ]);
        Event::fake([
            PageableUpdated::class,
        ]);
        $feedItem = $this->makeFeedItem([
            "Make" => strtoupper($manufacturer->name),
            "LayoutCode" => $layout->code,
        ]);
        $fetcherMock = Mockery::mock(Fetcher::class);
        $importer = new CaravanCreator($feedItem, $fetcherMock);

        $result = $importer->call();

        $this->assertTrue($result);

        Event::assertDispatched(PageableUpdated::class, 2); // Once for caravan stock item, once for caravan range.
    }

    private function makeFeedItem($overrides = [])
    {
        $defaults = [
            "Category" => "Caravan",
            "Condition" => "Used",
            "UniqueCode" => "44533",
            "Make" => "SWIFT",
            "Model" => "CHALLENGER 480 SE",
            "WebModel" => "Challenger",
            "Year" => "2007",
            "Berths" => "2",
            "Axles" => "SA",
            "OverallLength" => "6.42",
            "MTPLM" => "1360",
            "Location" => "MJ",
            "RegYear" => "2007-05-25",
            "Owners" => "4",
            "Price" => "6995", // "Price" actually means "NowPrice"
            "SalePrice" => "7995", // "SalePrice" actually means "WasPrice"
            "DeliveryDate" => "2020-01-01",
            "ReducedPriceDate" => "2020-02-02",
            "LayoutType" => "End Bathroom",
            "LayoutCode" => "EB",
            "EstimatedPayload" => "178",
            "Width" => "2.23",
            "Height" => "2.34",
            "VehicleDescription" => "This 2007 Swift Conqueror is built on a single-axle chassis with stabiliser. The 2-berth interior layout consists of a front lounge, central kitchen with storage opposite and a full width end washroom with separate shower compartment.\r\n\r\nStandard specification includes :",
            "Kris" => "SGDST5ASW70845947",
            "Exclusive" => true,
            "ManAuto" => "M",
            "MIRO" => "1182",
            "NoOwners" => "4",
            "VehicleFeatures" => [
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
            ],
            "Images" => []
        ];

        $data = array_merge($defaults, $overrides);
        return $data;
    }
}

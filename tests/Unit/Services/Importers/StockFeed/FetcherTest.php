<?php

namespace Tests\Unit\Services\Importers\StockFeed;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\Importers\StockFeed\Fetcher;
use Tests\Support\GuzzleMocks;

class FetcherTest extends TestCase
{
    use RefreshDatabase, GuzzleMocks;

    public function test_fetches_feed_data_and_decodes_json_response()
    {
        $response = $this->createGuzzleResponse([
            'status_code' => 200,
            'body' => $this->feedResponseBody(),
        ]);
        $client = $this->createGuzzleWithMockedResponses($response);
        $fetcher = new Fetcher($client);

        $result = $fetcher->getFeed();

        $this->assertEquals($this->feedData(), $result);
    }

    public function test_fetches_image_by_reference()
    {
        $testFile = $this->getTestJpg();
        $testFileData = file_get_contents($testFile);
        $response = $this->createGuzzleResponse([
            'status_code' => 200,
            'headers' => [
                'Content-Type' => 'image/jpeg',
            ],
            'body' => $testFileData,
        ]);
        $client = $this->createGuzzleWithMockedResponses($response);
        $fetcher = new Fetcher($client);

        $result = $fetcher->getImage('abc');

        $this->assertEquals(base64_encode($testFileData), $result['data']);
        $this->assertEquals('image/jpeg', $result['mime_type']);
    }

    private function feedData()
    {
        return [
            [
                "Category" => "Caravan",
                "Condition" => "Used",
                "UniqueCode" => "44533",
                "Make" => "SWIFT",
                "Model" => "CHALLENGER 480 SE",
                "Year" => "2007",
                "Berths" => "2",
                "Axles" => "SA",
                "OverallLength" => "6.42",
                "MTPLM" => "1360",
                "Location" => "MJ",
                "RegYear" => "2007-05-25",
                "Owners" => "4",
                "Price" => "5995",
                "SalePrice" => "5995",
                "LayoutType" => "End Bathroom",
                "LayoutCode" => "EB",
                "EstimatedPayload" => "178",
                "Width" => "2.23",
                "Height" => "2.34",
                "VehicleDescription" => "This 2007 Swift Conqueror is built on a single-axle chassis with stabiliser. The 2-berth interior layout consists of a front lounge, central kitchen with storage opposite and a full width end washroom with separate shower compartment.\r\n\r\nStandard specification includes :",
                "Kris" => "SGDST5ASW70845947",
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
            ],
            [
                "Category" => "Motorhome",
                "Condition" => "Used",
                "UniqueCode" => "44556",
                "Make" => "NUVENTURE",
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
                "Price" => "23995",
                "SalePrice" => "24995",
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
            ],
        ];
    }

    private function feedResponseBody()
    {
        return json_encode($this->feedData());
    }
}

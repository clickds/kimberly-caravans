<?php

use App\Models\Layout;
use App\Models\Manufacturer;
use App\Models\CaravanRange;
use App\Models\Caravan;
use App\Models\MotorhomeRange;
use App\Models\Motorhome;
use App\Models\Site;
use App\Models\User;
use App\Services\Pageable\ManufacturerPageSaver;
use App\Services\Pageable\CaravanRangePageSaver;
use App\Services\Pageable\MotorhomeRangePageSaver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ActualContentSeeder extends Seeder
{
    private $england;
    private $ireland;
    private $northernIreland;
    private $newZealand;
    private $scotland;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->states('super')->create([
            'email' => 'dev@clickdigitalsolutions.co.uk',
        ]);
        $this->createSites();
        $this->createLayouts();
        $this->createManufacturers();
        $this->createPages();
    }

    private function createSites()
    {
        $this->england = factory(Site::class)->create([
            'country' => 'England',
            'flag' => 'england.svg',
            'subdomain' => 'www',
            'is_default' => true,
            'has_stock' => true,
        ]);
        $this->ireland = factory(Site::class)->create([
            'country' => 'Ireland',
            'subdomain' => 'ie',
            'flag' => 'ireland.svg',
            'is_default' => false,
            'has_stock' => false,
        ]);
        $this->northernIreland = factory(Site::class)->create([
            'country' => 'Northern Ireland',
            'subdomain' => 'ni',
            'flag' => 'ireland.svg',
            'is_default' => false,
            'has_stock' => false,
        ]);
        $this->newZealand = factory(Site::class)->create([
            'country' => 'New Zealand',
            'subdomain' => 'nz',
            'flag' => 'new-zealand.svg',
            'is_default' => false,
            'has_stock' => false,
        ]);
        $this->scotland = factory(Site::class)->create([
            'country' => 'Scotland',
            'subdomain' => 'scot',
            'flag' => 'scotland.svg',
            'is_default' => false,
            'has_stock' => false,
        ]);
    }

    private function createLayouts()
    {
        $data = [
            [
                'code' => 'EK',
                'name' => 'End Kitchen',
            ],
            [
                'code' => 'FRB',
                'name' => 'Fixed Rear Bed',
            ],
            [
                'code' => 'TB',
                'name' => 'Twin Single Beds',
            ],
            [
                'code' => 'RLU',
                'name' => 'Rear U-Shaped Lounge',
            ],
            [
                'code' => 'RLL',
                'name' => 'Rear L-Shaped Lounge',
            ],
            [
                'code' => 'RLS',
                'name' => 'Rear Lounge (Settees)',
            ],
            [
                'code' => 'RD',
                'name' => 'Read Door entry (coachbuilt)',
            ],
            [
                'code' => 'VN',
                'name' => 'Van (No shower)',
            ],
            [
                'code' => 'VS',
                'name' => 'Van with Shower',
            ],
            [
                'code' => 'G',
                'name' => 'Garage',
            ],
            [
                'code' => 'BB',
                'name' => 'Bunk Beds',
            ],
            [
                'code' => 'DD',
                'name' => 'Double Dinette',
            ],
            [
                'code' => 'EB',
                'name' => 'End Bathroom',
            ],
            [
                'code' => 'FIB',
                'name' => 'Fixed Island Bed',
            ],
        ];

        foreach ($data as $layoutData) {
            factory(Layout::class)->create($layoutData);
        }
    }

    private function createPages()
    {
        Manufacturer::with('sites')->chunk(100, function ($manufacturers) {
            foreach ($manufacturers as $manufacturer) {
                $this->createManufacturerPages($manufacturer, $manufacturer->sites);
            }
        });

        CaravanRange::with('sites')->chunk(100, function ($caravanRanges) {
            foreach ($caravanRanges as $caravanRange) {
                $this->createCaravanRangePages($caravanRange, $caravanRange->sites);
            }
        });

        MotorhomeRange::with('sites')->chunk(100, function ($motorhomeRanges) {
            foreach ($motorhomeRanges as $motorhomeRange) {
                $this->createMotorhomeRangePages($motorhomeRange, $motorhomeRange->sites);
            }
        });
    }

    private function createManufacturers()
    {
        $this->createElddisMajestic();
        $this->createMobilvetta();
        $this->createBenimar();
        $this->createAutoSleepers();
        $this->createAutoTrail();
        $this->createAdria();
        $this->createElddis();
        $this->createCaravelair();
        $this->createVenus();
        $this->createCoachman();
        $this->createAlaria();
        $this->createXplore();
        $manufacturerIds = Manufacturer::pluck('id')->toArray();
        $this->england->manufacturers()->sync($manufacturerIds);
        $caravanRangeIds = CaravanRange::pluck('id')->toArray();
        $this->england->caravanRanges()->sync($caravanRangeIds);
        $motorhomeRangeIds = MotorhomeRange::pluck('id')->toArray();
        $this->england->motorhomeRanges()->sync($motorhomeRangeIds);
    }

    private function createXplore()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Xplore',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Xplore',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createXploreXploreModels($range);
    }

    private function createXploreXploreModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => 'Xplore 586',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 6,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'length' => 7.29,
            'width' => 2.18,
            'mro' => 1219,
            'mtplm' => 1400,
            'payload' => 129,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18789],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => 'Xplore 554',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.38,
            'width' => 2.18,
            'mro' => 1201,
            'mtplm' => 1350,
            'payload' => 102,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18789],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => 'Xplore 422',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.87,
            'width' => 2.18,
            'mro' => 1003,
            'mtplm' => 1200,
            'payload' => 74,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 16839],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => 'Xplore 304',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 5.54,
            'width' => 2.18,
            'mro' => 933,
            'mtplm' => 1100,
            'payload' => 88,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 15789],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAlaria()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Alaria',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Caravan Range',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAlariaCaravanRangeModels($range);
    }

    private function createAlariaCaravanRangeModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => 'Alaria TR',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.88,
            'width' => 2.46,
            'mro' => 1684,
            'mtplm' => 1845,
            'payload' => 161,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 34324],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => 'Alaria TS',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.88,
            'width' => 2.46,
            'mro' => 1684,
            'mtplm' => 1845,
            'payload' => 161,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 34324],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => 'Alaria TI',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.88,
            'width' => 2.46,
            'mro' => 1654,
            'mtplm' => 1815,
            'payload' => 161,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 34324],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createCoachman()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Coachman',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Acadia',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createCoachmanAcadiaModels($range);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'VIP',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createCoachmanVipModels($range);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Laser',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createCoachmanLaserModels($range);
    }

    private function createCoachmanLaserModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => 'XCEL 875',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.44,
            'mro' => 1725,
            'mtplm' => 1885,
            'payload' => 160,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 35945],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => 'XCEL 850',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.44,
            'mro' => 1767,
            'mtplm' => 1927,
            'payload' => 160,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 35945],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '675',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.31,
            'mro' => 1656,
            'mtplm' => 1816,
            'payload' => 160,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 31810],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '665',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.31,
            'mro' => 1678,
            'mtplm' => 1838,
            'payload' => 160,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 31810],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '650',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.31,
            'mro' => 1669,
            'mtplm' => 1829,
            'payload' => 160,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 31810],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createCoachmanVipModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '575',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.43,
            'width' => 2.31,
            'mro' => 1474,
            'mtplm' => 1630,
            'payload' => 156,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 28040],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '565',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.37,
            'width' => 2.31,
            'mro' => 1479,
            'mtplm' => 1634,
            'payload' => 155,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 28040],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '545',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.43,
            'width' => 2.31,
            'mro' => 1487,
            'mtplm' => 1643,
            'payload' => 156,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 28040],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '520',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 3,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.05,
            'width' => 2.31,
            'mro' => 1383,
            'mtplm' => 1525,
            'payload' => 142,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 27565],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '460',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.38,
            'width' => 2.31,
            'mro' => 1300,
            'mtplm' => 1425,
            'payload' => 125,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 25970],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createCoachmanAcadiaModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '860',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 5,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.44,
            'mro' => 1700,
            'mtplm' => 1870,
            'payload' => 170,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 27905],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '675',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.26,
            'mro' => 1555,
            'mtplm' => 1715,
            'payload' => 160,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 25945],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '630',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 5,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.89,
            'width' => 2.26,
            'mro' => 1555,
            'mtplm' => 1725,
            'payload' => 170,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 24945],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '580',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 5,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'length' => 7.34,
            'width' => 2.26,
            'mro' => 1429,
            'mtplm' => 1594,
            'payload' => 165,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 24495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '575',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.39,
            'width' => 2.26,
            'mro' => 1379,
            'mtplm' => 1534,
            'payload' => 155,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 24155],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '565',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.34,
            'width' => 2.26,
            'mro' => 1426,
            'mtplm' => 1581,
            'payload' => 155,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 24155],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '545',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.39,
            'width' => 2.26,
            'mro' => 1422,
            'mtplm' => 1597,
            'payload' => 155,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 24155],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '520',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 3,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.92,
            'width' => 2.26,
            'mro' => 1314,
            'mtplm' => 1455,
            'payload' => 141,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 23785],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '470',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.33,
            'width' => 2.26,
            'mro' => 1218,
            'mtplm' => 1343,
            'payload' => 125,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 22885],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '460',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.25,
            'width' => 2.26,
            'mro' => 1231,
            'mtplm' => 1355,
            'payload' => 124,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 22345],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createVenus()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Venus',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Caravan Range',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createVenusCaravanRangeModels($range);
    }

    private function createVenusCaravanRangeModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '620/6',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 6,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.4,
            'width' => 2.2,
            'mro' => 1300,
            'mtplm' => 1480,
            'payload' => 180,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 19824],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '590/6',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 6,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'length' => 7.39,
            'width' => 2.2,
            'mro' => 1204,
            'mtplm' => 1380,
            'payload' => 176,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18924],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '570/4',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.39,
            'width' => 2.2,
            'mro' => 1179,
            'mtplm' => 1335,
            'payload' => 156,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18424],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '550/4',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.2,
            'width' => 2.2,
            'mro' => 1166,
            'mtplm' => 1320,
            'payload' => 154,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18424],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '540/4',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.2,
            'width' => 2.2,
            'mro' => 1151,
            'mtplm' => 1305,
            'payload' => 154,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18224],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '460/2',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 5.15,
            'width' => 2.2,
            'mro' => 1031,
            'mtplm' => 1155,
            'payload' => 124,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 17024],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createCaravelair()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Caravelair',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Antarés',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createCaravelairAntaresModels($range);
    }

    private function createCaravelairAntaresModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '496',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 6,
            'exclusive' => true,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'length' => 5.85,
            'width' => 2.3,
            'mro' => 1217,
            'mtplm' => 1400,
            'payload' => 183,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 17995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '485',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => true,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.25,
            'width' => 2.3,
            'mro' => 1317,
            'mtplm' => 1500,
            'payload' => 183,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '480',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => true,
            'layout_id' => Layout::where('code', 'TB')->firstOrFail()->id,
            'length' => 6.25,
            'width' => 2.3,
            'mro' => 1327,
            'mtplm' => 1500,
            'payload' => 173,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 18995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '455',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => true,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.41,
            'width' => 2.3,
            'mro' => 1047,
            'mtplm' => 1250,
            'payload' => 203,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 16495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '406',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => true,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 5.51,
            'width' => 2.1,
            'mro' => 927,
            'mtplm' => 1150,
            'payload' => 223,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 15495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '335',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => true,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.01,
            'width' => 2.1,
            'mro' => 827,
            'mtplm' => 1000,
            'payload' => 173,
            'caravan_range_id' => $range->id,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 14495],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createElddis()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Elddis',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Autoquest',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createElddisAutoquestModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Accordo',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createElddisAccordoModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Encore',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createElddisEncoreModels($range);
    }

    private function createElddisEncoreModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '285',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 3040,
            'mtplm' => 3500,
            'payload' => 460,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56999],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '275',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 2990,
            'mtplm' => 3500,
            'payload' => 510,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56999],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '255',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 3050,
            'mtplm' => 3500,
            'payload' => 450,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56999],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '250',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 3070,
            'mtplm' => 3500,
            'payload' => 430,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56999],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createElddisAccordoModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '135',
            'berths' => 3,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.14,
            'mro' => 2643,
            'mtplm' => 3300,
            'payload' => 657,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 47249],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '120',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.14,
            'mro' => 2554,
            'mtplm' => 3300,
            'payload' => 746,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 46249],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '105',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.14,
            'mro' => 2588,
            'mtplm' => 3300,
            'payload' => 712,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 46749],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createElddisAutoquestModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '196',
            'berths' => 6,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 6,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 7.33,
            'width' => 2.2,
            'mro' => 2932,
            'mtplm' => 3500,
            'payload' => 568,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 50049],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '194',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.33,
            'width' => 2.2,
            'mro' => 2914,
            'mtplm' => 3500,
            'payload' => 586,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 49399],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '185',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.33,
            'width' => 2.2,
            'mro' => 2909,
            'mtplm' => 3500,
            'payload' => 591,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 49249],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '175',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.02,
            'width' => 2.2,
            'mro' => 2790,
            'mtplm' => 3500,
            'payload' => 710,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 47249],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '155',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.02,
            'width' => 2.2,
            'mro' => 2857,
            'mtplm' => 3500,
            'payload' => 643,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 47999],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '115',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.7,
            'width' => 2.2,
            'mro' => 2547,
            'mtplm' => 3300,
            'payload' => 753,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 42999],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdria()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Adria',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Sonic',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaSonicModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Matrix',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaMatrixModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Coral',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaCoralModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Coral XL',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaCoralXLModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Compact Plus',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaCompactPlusModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Twin',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaTwinModels($range);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Altea',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaAlteaModels($range);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Action',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaActionModels($range);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Adora',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaAdoraModels($range);

        $range = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Alpina',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAdriaAlpinaModels($range);
    }

    private function createAdriaAlpinaModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '613 UL Colorado',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 8.32,
            'width' => 2.46,
            'mro' => 1709,
            'mtplm' => 1900,
            'payload' => 191,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 30995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '613 UC Missouri',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 8.32,
            'width' => 2.46,
            'mro' => 1839,
            'mtplm' => 1900,
            'payload' => 61,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 30495],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaAdoraModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '623 DT Sava',
            'axles' => Caravan::AXLE_TWIN,
            'berths' => 5,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 8.29,
            'width' => 2.45,
            'mro' => 1715,
            'mtplm' => 1900,
            'payload' => 185,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 25495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '613 UT Thames',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 8.25,
            'width' => 2.45,
            'mro' => 1601,
            'mtplm' => 1750,
            'payload' => 149,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 23995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '613 DT Isonzo',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 8.24,
            'width' => 2.45,
            'mro' => 1596,
            'mtplm' => 1750,
            'payload' => 154,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 23995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '612 DL Seine',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 8.24,
            'width' => 2.29,
            'mro' => 1506,
            'mtplm' => 1600,
            'payload' => 94,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 23995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaActionModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '361 LT',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.21,
            'width' => 2.19,
            'mro' => 985,
            'mtplm' => 1100,
            'payload' => 115,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 17995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaAlteaModels(CaravanRange $range)
    {
        $model = factory(Caravan::class)->create([
            'name' => '622 DP DART',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 4,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 8.29,
            'width' => 2.29,
            'mro' => 1456,
            'mtplm' => 1650,
            'payload' => 194,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 20195],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '622 DK AVON',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 6,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'length' => 8.28,
            'width' => 2.29,
            'mro' => 1471,
            'mtplm' => 1650,
            'payload' => 179,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 19995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Caravan::class)->create([
            'name' => '492 DT AIRE',
            'axles' => Caravan::AXLE_SINGLE,
            'berths' => 2,
            'exclusive' => false,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.96,
            'width' => 2.29,
            'mro' => 1256,
            'mtplm' => 1420,
            'payload' => 164,
            'caravan_range_id' => $range->id,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 19395],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaTwinModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'Supreme 600SPB',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.05,
            'mro' => 2764,
            'mtplm' => 3300,
            'payload' => 536,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 47485],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Supreme 640SGX',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.05,
            'mro' => 2580,
            'mtplm' => 3500,
            'payload' => 920,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 48835],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Supreme 640SLB',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.05,
            'mro' => 2935,
            'mtplm' => 3500,
            'payload' => 565,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 47925],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Plus 640SLB',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.05,
            'mro' => 2936,
            'mtplm' => 3500,
            'payload' => 564,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 46925],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Plus 640SPX',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.05,
            'mro' => 2936,
            'mtplm' => 3500,
            'payload' => 564,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 46925],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Axess 600SP',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.05,
            'mro' => 2945,
            'mtplm' => 3300,
            'payload' => 355,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 44925],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaCompactPlusModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'Slide Out SLS',
            'berths' => 3,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.12,
            'mro' => 2821,
            'mtplm' => 3500,
            'payload' => 679,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54625],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'SL',
            'berths' => 3,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.62,
            'width' => 2.12,
            'mro' => 2783,
            'mtplm' => 3500,
            'payload' => 717,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 52925],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'SP',
            'berths' => 3,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.12,
            'mro' => 2703,
            'mtplm' => 3500,
            'payload' => 797,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 51925],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaCoralXLModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '670DK',
            'berths' => 7,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 7,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'length' => 7.29,
            'width' => 2.3,
            'mro' => 2934,
            'mtplm' => 3500,
            'payload' => 566,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 60295],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '670SP',
            'berths' => 6,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 5,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.29,
            'width' => 2.3,
            'mro' => 3072,
            'mtplm' => 3500,
            'payload' => 428,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 60295],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaCoralModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '670SC',
            'berths' => 3,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 5,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.5,
            'width' => 2.3,
            'mro' => 3152,
            'mtplm' => 3500,
            'payload' => 348,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '670SL',
            'berths' => 3,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 5,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.5,
            'width' => 2.3,
            'mro' => 3132,
            'mtplm' => 3500,
            'payload' => 368,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaMatrixModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '670SC',
            'berths' => 5,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 5,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.5,
            'width' => 2.3,
            'mro' => 3150,
            'mtplm' => 3500,
            'payload' => 350,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '670SL',
            'berths' => 5,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 5,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.38,
            'width' => 2.3,
            'mro' => 3099,
            'mtplm' => 3500,
            'payload' => 401,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAdriaSonicModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '710DC',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'engine_size' => 2300,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.52,
            'width' => 2.32,
            'mro' => 3500,
            'mtplm' => 4400,
            'payload' => 900,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 84755],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '710SC',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'engine_size' => 2300,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.52,
            'width' => 2.32,
            'mro' => 3237,
            'mtplm' => 3500,
            'payload' => 263,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 84225],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '710SL',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'engine_size' => 2300,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.52,
            'width' => 2.32,
            'mro' => 3237,
            'mtplm' => 3500,
            'payload' => 263,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 84225],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '810SC',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'engine_size' => 2300,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 8.8,
            'width' => 2.32,
            'mro' => 3937,
            'mtplm' => 5000,
            'payload' => 1063,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 94225],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '810SL',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'engine_size' => 2300,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 8.8,
            'width' => 2.32,
            'mro' => 3987,
            'mtplm' => 5000,
            'payload' => 1013,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2019,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 94225],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrail()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Auto-Trail',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Tribute Compact',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailTributeCompactModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Tribute Coachbuilt',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailTributeCoachbuiltModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'V-Line',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailVLineModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Adventure',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailAdventureModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Imala',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailImalaModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Tracker',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailTrackerModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Apache',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailApacheModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Frontier',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoTrailFrontierModels($range);
    }

    private function createAutoTrailFrontierModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'Delaware',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 8.07,
            'width' => 2.35,
            'mro' => 3750,
            'mtplm' => 4500,
            'payload' => 750,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 77015],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Delaware S',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 8.07,
            'width' => 2.35,
            'mro' => 3745,
            'mtplm' => 4500,
            'payload' => 755,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 77015],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Delaware HB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 8.07,
            'width' => 2.35,
            'mro' => 3735,
            'mtplm' => 4500,
            'payload' => 765,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 77015],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Scout',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'length' => 8.07,
            'width' => 2.35,
            'mro' => 3720,
            'mtplm' => 4500,
            'payload' => 780,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 77015],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Comanche',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 8.79,
            'width' => 2.35,
            'mro' => 4140,
            'mtplm' => 5000,
            'payload' => 860,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 88365],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Comanche S',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 8.79,
            'width' => 2.35,
            'mro' => 4150,
            'mtplm' => 5000,
            'payload' => 850,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 88365],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Comanche HB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 8.79,
            'width' => 2.35,
            'mro' => 4120,
            'mtplm' => 5000,
            'payload' => 880,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 88365],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrailApacheModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '632',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.36,
            'width' => 2.35,
            'mro' => 3300,
            'mtplm' => 3500,
            'payload' => 200,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 63135],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '634',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'length' => 7.36,
            'width' => 2.35,
            'mro' => 3300,
            'mtplm' => 3500,
            'payload' => 200,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 63135],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '700',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'length' => 7.54,
            'width' => 2.35,
            'mro' => 3510,
            'mtplm' => 4250,
            'payload' => 740,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 67220],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrailTrackerModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'EKS',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 6.44,
            'width' => 2.35,
            'mro' => 3200,
            'mtplm' => 3500,
            'payload' => 300,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 60170],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'RS',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.93,
            'width' => 2.35,
            'mro' => 3260,
            'mtplm' => 3500,
            'payload' => 240,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62455],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'FB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.13,
            'width' => 2.35,
            'mro' => 3300,
            'mtplm' => 3500,
            'payload' => 200,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 63130],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'EB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.36,
            'width' => 2.35,
            'mro' => 3440,
            'mtplm' => 4250,
            'payload' => 810,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 65365],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'RB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.6,
            'width' => 2.35,
            'mro' => 3500,
            'mtplm' => 4250,
            'payload' => 750,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 65365],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'LB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.6,
            'width' => 2.35,
            'mro' => 3500,
            'mtplm' => 4250,
            'payload' => 750,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 65365],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrailImalaModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '615',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 6.34,
            'width' => 2.35,
            'mro' => 3050,
            'mtplm' => 3500,
            'payload' => 450,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 52550],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '625',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'length' => 6.34,
            'width' => 2.35,
            'mro' => 3060,
            'mtplm' => 3500,
            'payload' => 440,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 52550],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '720',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 7.05,
            'width' => 2.35,
            'mro' => 3140,
            'mtplm' => 3500,
            'payload' => 360,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56100],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '730',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.25,
            'width' => 2.35,
            'mro' => 3240,
            'mtplm' => 3500,
            'payload' => 260,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '730HB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.25,
            'width' => 2.35,
            'mro' => 3200,
            'mtplm' => 3500,
            'payload' => 300,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '732',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'length' => 7.25,
            'width' => 2.35,
            'mro' => 3200,
            'mtplm' => 3500,
            'payload' => 300,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '734',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 7.25,
            'width' => 2.35,
            'mro' => 3260,
            'mtplm' => 3500,
            'payload' => 240,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '734HB',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 7.25,
            'width' => 2.35,
            'mro' => 3260,
            'mtplm' => 3500,
            'payload' => 240,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '736',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'length' => 7.25,
            'width' => 2.35,
            'mro' => 3140,
            'mtplm' => 3500,
            'payload' => 360,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '736G',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.25,
            'width' => 2.35,
            'mro' => 3140,
            'mtplm' => 3500,
            'payload' => 360,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56690],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrailAdventureModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '55',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.27,
            'mro' => 3050,
            'mtplm' => 3500,
            'payload' => 450,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 58295],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '65',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.27,
            'mro' => 3135,
            'mtplm' => 3500,
            'payload' => 365,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 58895],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrailVLineModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '540SE',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.41,
            'width' => 2.27,
            'mro' => 2850,
            'mtplm' => 3500,
            'payload' => 650,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 51205],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '610SE',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.27,
            'mro' => 3000,
            'mtplm' => 3500,
            'payload' => 500,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 52950],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '634SE',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.27,
            'mro' => 3065,
            'mtplm' => 3500,
            'payload' => 435,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '635SE',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.27,
            'mro' => 3080,
            'mtplm' => 3500,
            'payload' => 420,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54690],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '636SE',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.27,
            'mro' => 3030,
            'mtplm' => 3500,
            'payload' => 470,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54690],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrailTributeCoachbuiltModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'F60',
            'berths' => 2,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.35,
            'mro' => 2860,
            'mtplm' => 3500,
            'payload' => 640,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 46860],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'F62',
            'berths' => 2,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.35,
            'mro' => 2870,
            'mtplm' => 3500,
            'payload' => 630,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 46860],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'F70',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.34,
            'width' => 2.35,
            'mro' => 3000,
            'mtplm' => 3500,
            'payload' => 500,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 48360],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'F72',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'length' => 7.34,
            'width' => 2.35,
            'mro' => 3000,
            'mtplm' => 3500,
            'payload' => 500,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 48360],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoTrailTributeCompactModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '660',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.27,
            'mro' => 2950,
            'mtplm' => 3500,
            'payload' => 550,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 44015],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '669',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.27,
            'mro' => 3030,
            'mtplm' => 3500,
            'payload' => 470,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 44840],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '670',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.27,
            'mro' => 3020,
            'mtplm' => 3500,
            'payload' => 480,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 46860],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '680',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.27,
            'mro' => 3080,
            'mtplm' => 3500,
            'payload' => 420,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 45940],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoSleepers()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Auto-Sleepers',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Mercedes Coachbuilt',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoSleepersMercedesCoachbuiltModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Peugeot Coachbuilt',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoSleepersPeugeotCoachbuiltModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Peugeot Van Conversions',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoSleepersPeugeotVanConversionModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Peugeot AL-KO Coachbuilts',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createAutoSleepersPeugeotAlKoCoachbuiltModels($range);
    }

    private function createAutoSleepersPeugeotAlKoCoachbuiltModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'Corinium FB',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.75,
            'width' => 2.32,
            'mro' => 3277,
            'mtplm' => 4000,
            'payload' => 723,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 74495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Corinium Duo',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.75,
            'width' => 2.35,
            'mro' => 3187,
            'mtplm' => 3500,
            'payload' => 313,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 73495],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoSleepersPeugeotVanConversionModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'Symbol',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 3,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.41,
            'width' => 2.26,
            'mro' => 2850,
            'mtplm' => 3300,
            'payload' => 450,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Symbol Plus',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 3,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.26,
            'mro' => 3010,
            'mtplm' => 3500,
            'payload' => 490,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 60495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Warwick Duo',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 5.99,
            'width' => 2.26,
            'mro' => 2892,
            'mtplm' => 3500,
            'payload' => 608,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 60495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Fairford',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.26,
            'mro' => 3111,
            'mtplm' => 3500,
            'payload' => 389,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Fairford Plus',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.26,
            'mro' => 3111,
            'mtplm' => 3500,
            'payload' => 389,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Kemerton XL',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 3,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.26,
            'mro' => 3089,
            'mtplm' => 3500,
            'payload' => 411,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Kingham',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.26,
            'mro' => 3102,
            'mtplm' => 3500,
            'payload' => 398,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Warwick XL',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'length' => 6.36,
            'width' => 2.26,
            'mro' => 3066,
            'mtplm' => 3500,
            'payload' => 434,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoSleepersPeugeotCoachbuiltModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'Nuevo EK',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.75,
            'width' => 2.32,
            'mro' => 2874,
            'mtplm' => 3500,
            'payload' => 626,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Nuevo ES',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.75,
            'width' => 2.32,
            'mro' => 2986,
            'mtplm' => 3500,
            'payload' => 514,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 62895],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Broadway EB',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 6.32,
            'width' => 2.32,
            'mro' => 3031,
            'mtplm' => 3500,
            'payload' => 469,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 66995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Broadway EK',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 6.32,
            'width' => 2.32,
            'mro' => 3131,
            'mtplm' => 3500,
            'payload' => 369,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 67995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Broadway EK TB LP',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 6.32,
            'width' => 2.32,
            'mro' => 3019,
            'mtplm' => 3500,
            'payload' => 481,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 66995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Broadway FB',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.21,
            'width' => 2.32,
            'mro' => 3162,
            'mtplm' => 3500,
            'payload' => 338,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 67995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createAutoSleepersMercedesCoachbuiltModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'Stanton',
            'berths' => 2,
            'chassis_manufacturer' => 'Mercedes',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_AUTOMATIC,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 5.98,
            'width' => 2.26,
            'mro' => 2846,
            'mtplm' => 3200,
            'payload' => 354,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 73995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Bourton',
            'berths' => 2,
            'chassis_manufacturer' => 'Mercedes',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_AUTOMATIC,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'length' => 6.44,
            'width' => 2.26,
            'mro' => 2980,
            'mtplm' => 3500,
            'payload' => 520,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 77995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Winchcombe',
            'berths' => 2,
            'chassis_manufacturer' => 'Mercedes',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_AUTOMATIC,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.33,
            'width' => 2.35,
            'mro' => 3235,
            'mtplm' => 4100,
            'payload' => 865,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 82495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Malvern',
            'berths' => 4,
            'chassis_manufacturer' => 'Mercedes',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_AUTOMATIC,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'length' => 7.33,
            'width' => 2.35,
            'mro' => 3218,
            'mtplm' => 4100,
            'payload' => 882,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 82495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Burford',
            'berths' => 4,
            'chassis_manufacturer' => 'Mercedes',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_AUTOMATIC,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.92,
            'width' => 2.35,
            'mro' => 3450,
            'mtplm' => 4100,
            'payload' => 650,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 84995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'Burford Duo',
            'berths' => 4,
            'chassis_manufacturer' => 'Mercedes',
            'designated_seats' => 2,
            'transmission' => Motorhome::TRANSMISSION_AUTOMATIC,
            'exclusive' => false,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'length' => 7.92,
            'width' => 2.35,
            'mro' => 3380,
            'mtplm' => 4100,
            'payload' => 720,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 84995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createBenimar()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Benimar',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Benimar Mileo',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createBenimarMileoModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Benimar Tessoro',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createBenimarTessoroModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Benimar Primero',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createBenimarPrimeroModels($range);
    }

    private function createBenimarPrimeroModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '202',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 5.99,
            'width' => 2.3,
            'mro' => 2870,
            'mtplm' => 3500,
            'payload' => 630,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 50995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '283',
            'berths' => 3,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 5.99,
            'width' => 2.3,
            'mro' => 2810,
            'mtplm' => 3500,
            'payload' => 690,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 48995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '301',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 3.05,
            'length' => 5.99,
            'width' => 2.3,
            'mro' => 2870,
            'mtplm' => 3500,
            'payload' => 630,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 48995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '313',
            'berths' => 6,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 6,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'height' => 3.05,
            'length' => 5.99,
            'width' => 2.3,
            'mro' => 2910,
            'mtplm' => 3500,
            'payload' => 590,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 48995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '331',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 3.05,
            'length' => 5.99,
            'width' => 2.3,
            'mro' => 2860,
            'mtplm' => 3500,
            'payload' => 640,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_COACHBUILT,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 48995],
        ]);
        $this->outputCreationMessage($model->name);
    }


    private function createBenimarTessoroModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '413',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'BB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 6.41,
            'width' => 2.3,
            'mro' => 3085,
            'mtplm' => 3500,
            'payload' => 415,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 55995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '463',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 7.38,
            'width' => 2.3,
            'mro' => 3200,
            'mtplm' => 3500,
            'payload' => 300,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '481',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 5.98,
            'width' => 2.3,
            'mro' => 2995,
            'mtplm' => 3500,
            'payload' => 505,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '482',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 6.98,
            'width' => 2.3,
            'mro' => 3200,
            'mtplm' => 3500,
            'payload' => 300,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 61995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '483',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'G')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 6.7,
            'width' => 2.3,
            'mro' => 3055,
            'mtplm' => 3500,
            'payload' => 445,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 58995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '486',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'G')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 6.98,
            'width' => 2.3,
            'mro' => 3110,
            'mtplm' => 3500,
            'payload' => 390,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 58995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '494',
            'berths' => 4,
            'chassis_manufacturer' => 'Ford',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 7.38,
            'width' => 2.3,
            'mro' => 3200,
            'mtplm' => 3500,
            'payload' => 300,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 60995],
        ]);
        $this->outputCreationMessage($model->name);
    }


    private function createBenimarMileoModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '201',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 5.95,
            'width' => 2.3,
            'mro' => 2940,
            'mtplm' => 3500,
            'payload' => 560,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '202',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 5.95,
            'width' => 2.3,
            'mro' => 2980,
            'mtplm' => 3500,
            'payload' => 520,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 56995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '231',
            'berths' => 2,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 5.95,
            'width' => 2.3,
            'mro' => 2940,
            'mtplm' => 3500,
            'payload' => 560,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '243',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 6.99,
            'width' => 2.3,
            'mro' => 3180,
            'mtplm' => 3500,
            'payload' => 320,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 58995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '282',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLU')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 7.39,
            'width' => 2.3,
            'mro' => 3220,
            'mtplm' => 3500,
            'payload' => 280,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 61995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '286',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 6.95,
            'width' => 2.3,
            'mro' => 3130,
            'mtplm' => 3500,
            'payload' => 370,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 58995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '294',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 7.35,
            'width' => 2.3,
            'mro' => 3240,
            'mtplm' => 3500,
            'payload' => 260,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 60995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createMobilvetta()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Mobilvetta',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Tekno Line K.Yacht',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createTeknoLineKYachtModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Tekno Line Kea P',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createTeknoLineKeaPModels($range);
    }

    private function createTeknoLineKeaPModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => 'P65',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 3,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'TB')->firstOrFail()->id,
            'height' => 2.9,
            'length' => 7.38,
            'width' => 2.35,
            'mro' => 3230,
            'mtplm' => 3500,
            'payload' => 270,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 69995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'P67',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 3,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 2.9,
            'length' => 6.99,
            'width' => 2.35,
            'mro' => 3230,
            'mtplm' => 3500,
            'payload' => 270,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 69995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => 'P68',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 3,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'height' => 2.9,
            'length' => 7.38,
            'width' => 2.35,
            'mro' => 3230,
            'mtplm' => 3500,
            'payload' => 270,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 69995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createTeknoLineKYachtModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '79',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 7.44,
            'width' => 2.35,
            'mro' => 3200,
            'mtplm' => 3650,
            'payload' => 450,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 78995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '80',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLL')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 6.99,
            'width' => 2.35,
            'mro' => 3200,
            'mtplm' => 3650,
            'payload' => 450,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 78995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '85',
            'berths' => 4,
            'chassis_manufacturer' => 'Fiat',
            'designated_seats' => 4,
            'engine_size' => 2300,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'TB')->firstOrFail()->id,
            'height' => 2.89,
            'length' => 7.44,
            'width' => 2.35,
            'mro' => 3200,
            'mtplm' => 3650,
            'payload' => 450,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 78995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createElddisMajestic()
    {
        $manufacturer = factory(Manufacturer::class)->create([
            'name' => 'Elddis Majestic',
        ]);
        $this->outputCreationMessage($manufacturer->name);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Majestic Coachbuilts',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createMajesticCoachbuiltModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Majestic Compact Coachbuilts',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createMajesticCompactCoachbuiltModels($range);

        $range = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Majestic Premium Coachbuilts',
        ]);
        $this->outputCreationMessage($range->name);
        $this->createMajesticPremiumCoachbuiltModels($range);
    }

    private function createMajesticPremiumCoachbuiltModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '250',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FIB')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 3105,
            'mtplm' => 3500,
            'payload' => 395,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '255',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 3085,
            'mtplm' => 3500,
            'payload' => 415,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '275',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 3026,
            'mtplm' => 3500,
            'payload' => 474,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '285',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.4,
            'width' => 2.35,
            'mro' => 3075,
            'mtplm' => 3500,
            'payload' => 425,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 59995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createMajesticCoachbuiltModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '115',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EK')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 5.7,
            'width' => 2.2,
            'mro' => 2606,
            'mtplm' => 3300,
            'payload' => 694,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 49995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '155',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'FRB')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.02,
            'width' => 2.2,
            'mro' => 2924,
            'mtplm' => 3500,
            'payload' => 576,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 53995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '175',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.02,
            'width' => 2.2,
            'mro' => 2849,
            'mtplm' => 3500,
            'payload' => 651,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 52995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '185',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.33,
            'width' => 2.2,
            'mro' => 2976,
            'mtplm' => 3500,
            'payload' => 524,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '194',
            'berths' => 4,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'height' => 2.82,
            'length' => 7.33,
            'width' => 2.2,
            'mro' => 2981,
            'mtplm' => 3500,
            'payload' => 519,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 54995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '196',
            'berths' => 6,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 6,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'height' => 2.87,
            'length' => 7.33,
            'width' => 2.2,
            'mro' => 2992,
            'mtplm' => 3500,
            'payload' => 508,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 55995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function createMajesticCompactCoachbuiltModels(MotorhomeRange $range)
    {
        $model = factory(Motorhome::class)->create([
            'name' => '105',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'EB')->firstOrFail()->id,
            'height' => 2.725,
            'length' => 5.99,
            'width' => 2.14,
            'mro' => 2624,
            'mtplm' => 3300,
            'payload' => 676,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 51495],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '120',
            'berths' => 2,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 2,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'height' => 2.725,
            'length' => 5.99,
            'width' => 2.14,
            'mro' => 2593,
            'mtplm' => 3300,
            'payload' => 707,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 50995],
        ]);
        $this->outputCreationMessage($model->name);

        $model = factory(Motorhome::class)->create([
            'name' => '135',
            'berths' => 3,
            'chassis_manufacturer' => 'Peugeot',
            'designated_seats' => 4,
            'engine_size' => null,
            'transmission' => Motorhome::TRANSMISSION_MANUAL,
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_TURBO_DIESEL,
            'layout_id' => Layout::where('code', 'RLS')->firstOrFail()->id,
            'height' => 2.725,
            'length' => 5.99,
            'width' => 2.14,
            'mro' => 2679,
            'mtplm' => 3300,
            'payload' => 621,
            'motorhome_range_id' => $range->id,
            'conversion' => Motorhome::CONVERSION_CAMPERVAN,
            'year' => 2020,
        ]);
        $model->sites()->sync([
            $this->england->id => ['price' => 51995],
        ]);
        $this->outputCreationMessage($model->name);
    }

    private function outputCreationMessage($output)
    {
        $this->command->line("<info>Creating:</info> {$output}");
    }

    private function createManufacturerPages(Manufacturer $manufacturer, Collection $sites)
    {
        $sites->each(function ($site) use ($manufacturer) {
            $saver = new ManufacturerPageSaver($manufacturer, $site);
            $saver->call();
            $this->outputCreationMessage("Page for {$manufacturer->name} {$site->country}");
        });
    }

    private function createMotorhomeRangePages(MotorhomeRange $motorhomeRange, Collection $sites)
    {
        $sites->each(function ($site) use ($motorhomeRange) {
            $saver = new MotorhomeRangePageSaver($motorhomeRange, $site);
            $saver->call();
            $this->outputCreationMessage("Page for {$motorhomeRange->name} {$site->country}");
        });
    }

    private function createCaravanRangePages(CaravanRange $caravanRange, Collection $sites)
    {
        $sites->each(function ($site) use ($caravanRange) {
            $saver = new CaravanRangePageSaver($caravanRange, $site);
            $saver->call();
            $this->outputCreationMessage("Page for {$caravanRange->name} {$site->country}");
        });
    }
}

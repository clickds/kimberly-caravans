<?php

namespace Tests\Unit\Presenters\PanelType;

use App\Models\Area;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\PanelType\BasePanelPresenter as PanelPresenter;
use App\Models\Panel;

class BasePanelPresenterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider columnsProvider
     */
    public function test_area_columns(int $columns): void
    {
        $area = factory(Area::class)->make([
            'columns' => $columns,
        ]);
        $panel = factory(Panel::class)->make();
        $panel->setRelation('area', $area);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->areaColumns();

        $this->assertEquals($columns, $result);
    }

    public function columnsProvider(): array
    {
        return [
            [1],
            [2],
        ];
    }

    /**
     * @dataProvider headingClassesProvider
     */
    public function test_heading_css_classes(
        string $areaBackgroundColour,
        string $headingType,
        string $expectedCssClass
    ): void {
        $area = factory(Area::class)->make([
            'background_colour' => $areaBackgroundColour,
        ]);
        $panel = factory(Panel::class)->make([
            'heading_type' => $headingType,
        ]);
        $panel->setRelation('area', $area);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertStringContainsString($expectedCssClass, $presenter->headingCssClasses());
    }

    public function headingClassesProvider(): array
    {
        return [
            ['endeavour', 'h1', 'text-white'],
            ['endeavour', 'h2', 'text-white'],
            ['endeavour', 'h3', 'text-web-orange'],
            ['shiraz', 'h1', 'text-white'],
            ['shiraz', 'h2', 'text-white'],
            ['shiraz', 'h3', 'text-web-orange'],
            ['web-orange', 'h1', 'text-white'],
            ['web-orange', 'h2', 'text-white'],
            ['web-orange', 'h3', 'text-endeavour'],
            ['alabaster', 'h1', 'text-regal-blue'],
            ['alabaster', 'h2', 'text-regal-blue'],
            ['alabaster', 'h3', 'text-endeavour'],
            ['gallery', 'h1', 'text-regal-blue'],
            ['gallery', 'h2', 'text-regal-blue'],
            ['gallery', 'h3', 'text-endeavour'],
            ['white', 'h1', 'text-regal-blue'],
            ['white', 'h2', 'text-regal-blue'],
            ['white', 'h3', 'text-endeavour'],
        ];
    }

    public function test_get_vehicle_type(): void
    {
        $vehicleType = Panel::VEHICLE_TYPE_BOTH;
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_STANDARD,
            'vehicle_type' => $vehicleType,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertEquals($vehicleType, $presenter->getVehicleType());
    }

    /**
     * @dataProvider displayCaravansProvider
     */
    public function test_display_caravans(string $vehicleType, bool $expectedResult): void
    {
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_STANDARD,
            'vehicle_type' => $vehicleType,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertEquals($expectedResult, $presenter->displayCaravans());
    }

    public function displayCaravansProvider(): array
    {
        return [
            [
                Panel::VEHICLE_TYPE_BOTH,
                true,
            ],
            [
                Panel::VEHICLE_TYPE_CARAVAN,
                true,
            ],
            [
                Panel::VEHICLE_TYPE_MOTORHOME,
                false,
            ],
        ];
    }

    /**
     * @dataProvider displayMotorhomesProvider
     */
    public function test_display_motorhomes(string $vehicleType, bool $expectedResult): void
    {
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_STANDARD,
            'vehicle_type' => $vehicleType,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertEquals($expectedResult, $presenter->displayMotorhomes());
    }

    public function displayMotorhomesProvider(): array
    {
        return [
            [
                Panel::VEHICLE_TYPE_BOTH,
                true,
            ],
            [
                Panel::VEHICLE_TYPE_CARAVAN,
                false,
            ],
            [
                Panel::VEHICLE_TYPE_MOTORHOME,
                true,
            ],
        ];
    }
    /**
     * @dataProvider typesProvider
     */
    public function test_partial_path($type): void
    {
        $panel = factory(Panel::class)->make([
            'type' => $type,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->partialPath();

        $this->assertEquals("site.panels.{$type}.main", $result);
    }

    public function typesProvider(): array
    {
        return [
            array_keys(Panel::TYPES),
        ];
    }

    /**
     * @dataProvider verticalPositioningProvider
     */
    public function test_css_classes($verticalPositioning, $gridClass): void
    {
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_STANDARD,
            'vertical_positioning' => $verticalPositioning
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->cssClasses();

        $this->assertStringContainsString("panel", $result);
        $this->assertStringContainsString($gridClass, $result);
    }

    public function verticalPositioningProvider(): array
    {
        $positions = array_keys(Panel::VERTICAL_POSITIONS);

        return array_map(function ($position) {
            return [$position, $this->gridClassForPosition($position)];
        }, $positions);
    }

    private function gridClassForPosition($position): string
    {
        switch ($position) {
            case Panel::POSITION_TOP:
                return "grid-self-start";
            case Panel::POSITION_MIDDLE:
                return "grid-self-center";
            case Panel::POSITION_BOTTOM:
                return "grid-self-end";
            case Panel::POSITION_STRETCH:
                return "grid-self-stretch";
        }
    }
}

<?php

namespace Tests\Unit\Presenters;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\AreaPresenter;
use App\Models\Area;

class AreaPresenterTest extends TestCase
{
    use RefreshDatabase;

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
            'heading_type' => $headingType,
        ]);
        $presenter = (new AreaPresenter())->setWrappedObject($area);

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

    public function test_css_classes_on_fluid_width_area()
    {
        $area = factory(Area::class)->make([
            'width' => Area::FLUID_WIDTH,
        ]);
        $presenter = (new AreaPresenter())->setWrappedObject($area);

        $result = $presenter->cssClasses();

        $this->assertStringContainsString("area", $result);
        $this->assertStringContainsString("bg-{$area->background_colour}", $result);
    }

    public function test_css_classes_on_standard_width_area()
    {
        $area = factory(Area::class)->make([
            'width' => Area::STANDARD_WIDTH,
        ]);
        $presenter = (new AreaPresenter())->setWrappedObject($area);

        $result = $presenter->cssClasses();

        $this->assertStringContainsString("area", $result);
        $this->assertStringContainsString("bg-{$area->background_colour}", $result);
    }

    public function test_grid_classes_when_one_column_area()
    {
        $area = factory(Area::class)->make([
            'columns' => 1,
        ]);
        $presenter = (new AreaPresenter())->setWrappedObject($area);

        $result = $presenter->gridCssClasses();

        $this->assertStringContainsString("grid", $result);
        $this->assertStringContainsString("grid-cols-1", $result);
    }

    public function test_grid_classes_when_two_column_area()
    {
        $area = factory(Area::class)->make([
            'columns' => 2,
        ]);
        $presenter = (new AreaPresenter())->setWrappedObject($area);

        $result = $presenter->gridCssClasses();

        $this->assertStringContainsString("grid", $result);
        $this->assertStringContainsString("grid-cols-1", $result);
        $this->assertStringContainsString("lg:grid-cols-2", $result);
    }
}

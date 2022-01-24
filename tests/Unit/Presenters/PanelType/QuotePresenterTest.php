<?php

namespace Tests\Unit\Presenters\PanelType;

use App\Models\Area;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\PanelType\QuotePresenter as PanelPresenter;
use App\Models\Panel;

class QuotePresenterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider colourClassesProvider
     */
    public function test_quote_css_colour_class(string $areaBackgroundColour, string $textClass): void
    {
        $area = factory(Area::class)->make([
            'background_colour' => $areaBackgroundColour,
        ]);
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_QUOTE,
        ]);
        $panel->setRelation('area', $area);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->quoteCssColourClass();

        $this->assertEquals($textClass, $result);
    }

    public function colourClassesProvider(): array
    {
        return [
            ['endeavour', 'text-web-orange'],
            ['shiraz', 'text-web-orange'],
            ['web-orange', 'text-endeavour'],
            ['alabaster', 'text-endeavour'],
            ['gallery', 'text-endeavour'],
            ['white', 'text-endeavour'],
        ];
    }
}

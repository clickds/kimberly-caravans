<?php

namespace Tests\Unit\Presenters\PanelType;

use App\Models\Area;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\PanelType\ReadMorePresenter as PanelPresenter;
use App\Models\Panel;

class ReadMorePresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_read_more_content(): void
    {
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_READ_MORE,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertEquals($panel->read_more_content, $presenter->getReadMoreContent());
    }

    /**
     * @dataProvider buttonClassesProvider
     */
    public function test_toggle_button_css_classes(string $areaBackgroundColour, string $textClass): void
    {
        $area = factory(Area::class)->make([
            'background_colour' => $areaBackgroundColour,
        ]);
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_READ_MORE,
        ]);
        $panel->setRelation('area', $area);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->toggleButtonCssClasses();

        $this->assertStringContainsString($textClass, $result);
        $this->assertStringContainsString('block', $result);
        $this->assertStringContainsString('toggle-button', $result);
        $this->assertStringContainsString('font-heading', $result);
        $this->assertStringContainsString('font-bold', $result);
    }

    public function buttonClassesProvider(): array
    {
        return [
            ['endeavour', 'text-white'],
            ['shiraz', 'text-white'],
            ['web-orange', 'text-white'],
            ['alabaster', 'text-regal-blue'],
            ['gallery', 'text-regal-blue'],
            ['white', 'text-regal-blue'],
        ];
    }
}

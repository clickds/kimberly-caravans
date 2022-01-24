<?php

namespace Tests\Unit\Presenters\Cta;

use App\Models\Cta;
use App\Presenters\Cta\StandardTypePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StandardTypePresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_displayable(): void
    {
        $cta = new Cta();
        $presenter = (new StandardTypePresenter())->setWrappedObject($cta);

        $this->assertTrue($presenter->displayable());
    }

    public function test_page_when_page_exists(): void
    {
        $cta = factory(Cta::class)->create();
        $presenter = (new StandardTypePresenter())->setWrappedObject($cta);

        $this->assertEquals($cta->page->id, $presenter->page()->id);
    }

    public function test_page_when_no_page_exists(): void
    {
        $cta = new Cta();
        $presenter = (new StandardTypePresenter())->setWrappedObject($cta);

        $this->assertNull($presenter->page()->id);
    }

    public function test_partial_path(): void
    {
        $cta = new Cta();
        $presenter = (new StandardTypePresenter())->setWrappedObject($cta);

        $result = $presenter->partialPath();

        $this->assertEquals('ctas._standard', $result);
    }
}

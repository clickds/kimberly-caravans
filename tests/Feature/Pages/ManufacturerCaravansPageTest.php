<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;
use App\Facades\Manufacturer\CaravansPage;
use App\Models\CaravanRange;

class ManufacturerCaravansPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $parentPage = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_NEW_CARAVANS,
        ]);
        $manufacturer = factory(Manufacturer::class)->create();
        $page = $manufacturer->pages()->create([
            'parent_id' => $parentPage->id,
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
            'name' => $manufacturer->name . " Caravans",
            'meta_title' => $manufacturer->name . " Caravans",
        ]);
        $caravanRange = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
        ]);
        $caravanRange->sites()->sync($site->id);

        $this->withoutExceptionHandling();
        $response = $this->get($parentPage->slug . '/' . $page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(CaravansPage::class, $facade);
        $response->assertSee($manufacturer->name, true);
        $response->assertSee($caravanRange->name, true);
        $response->assertSee($caravanRange->overview);
    }

    public function test_does_not_show_ranges_for_other_sites()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $parentPage = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_NEW_CARAVANS,
        ]);
        $manufacturer = factory(Manufacturer::class)->create();
        $page = $manufacturer->pages()->create([
            'parent_id' => $parentPage->id,
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
            'name' => $manufacturer->name . " Caravans",
            'meta_title' => $manufacturer->name . " Caravans",
        ]);
        $caravanRange = factory(CaravanRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
        ]);

        $response = $this->get($parentPage->slug . '/' . $page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(CaravansPage::class, $facade);
        $response->assertSeeText($manufacturer->name);
        $response->assertDontSeeText($caravanRange->name);
        $response->assertDontSee($caravanRange->overview);
    }
}

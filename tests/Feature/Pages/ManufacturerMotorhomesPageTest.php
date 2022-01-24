<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Site;
use App\Facades\Manufacturer\MotorhomesPage;

class ManufacturerMotorhomesPageTest extends TestCase
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
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
            'name' => $manufacturer->name . " Motorhomes",
            'meta_title' => $manufacturer->name . " Motorhomes",
        ]);
        $motorhomeRange = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
        ]);
        $motorhomeRange->sites()->sync([$site->id]);

        $response = $this->get($parentPage->slug . '/' . $page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(MotorhomesPage::class, $facade);
        $response->assertSee($manufacturer->name);
        $response->assertSee($motorhomeRange->name);
        $response->assertSee($motorhomeRange->overview);
    }

    public function test_does_not_show_motorhome_ranges_that_does_not_belong_to_site()
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
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
            'name' => $manufacturer->name . " Motorhomes",
            'meta_title' => $manufacturer->name . " Motorhomes",
        ]);
        $motorhomeRange = factory(MotorhomeRange::class)->create([
            'manufacturer_id' => $manufacturer->id,
        ]);

        $response = $this->get($parentPage->slug . '/' . $page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(MotorhomesPage::class, $facade);
        $response->assertSee($manufacturer->name);
        $response->assertDontSee($motorhomeRange->name);
        $response->assertDontSee($motorhomeRange->overview);
    }
}

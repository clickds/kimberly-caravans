<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Page;
use App\Models\Site;
use App\Facades\NewMotorhomesPage;

class NewMotorhomesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_NEW_MOTORHOMES,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(NewMotorhomesPage::class, $facade);
    }
}

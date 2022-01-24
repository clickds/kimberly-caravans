<?php

namespace Tests\Feature\Pages;

use App\Models\Alias;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AliasRedirectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_alias_redirects(): void
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
        ]);
        $alias = factory(Alias::class)->create([
            'site_id' => $site->id,
            'page_id' => $page->id,
        ]);

        $response = $this->get($alias->capture_path);
        $response->assertRedirect(route('site', [
            'page' => $page->slug,
        ]));
    }
}

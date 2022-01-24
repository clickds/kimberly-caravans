<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\AliasRedirection;
use App\Models\Alias;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AliasRedirectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_redirected_when_no_alias(): void
    {
        $site = factory(Site::class)->create();
        $this->app->instance('currentSite', $site);
        $request = Request::create('/some-path', 'GET');

        $middleware = new AliasRedirection;

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response, null);
    }

    public function test_not_redirected_if_matching_alias_for_other_site(): void
    {
        $site = factory(Site::class)->create();
        $otherSite = factory(Site::class)->create();
        $alias = factory(Alias::class)->create([
            'capture_path' => '/some-path',
            'site_id' => $otherSite->id,
        ]);
        $this->app->instance('currentSite', $site);
        $request = Request::create($alias->capture_path, 'GET');

        $middleware = new AliasRedirection;

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response, null);
    }

    public function test_if_matching_alias_is_redirected(): void
    {
        $site = factory(Site::class)->create();
        $alias = factory(Alias::class)->create([
            'capture_path' => '/some-path',
            'site_id' => $site->id,
        ]);
        $this->app->instance('currentSite', $site);
        $request = Request::create($alias->capture_path, 'GET');

        $middleware = new AliasRedirection;

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 302);
        $location = $response->getTargetUrl();
        $this->assertStringContainsString($alias->page->slug, $location);
    }
}

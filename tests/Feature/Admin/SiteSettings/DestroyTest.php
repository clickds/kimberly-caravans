<?php

namespace Tests\Feature\Admin\SiteSettings;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful(): void
    {
        $siteSetting = factory(SiteSetting::class)->create();

        $response = $this->submit($siteSetting);

        $response->assertRedirect(route('admin.site-settings.index'));

        $this->assertDatabaseMissing('site_settings', $siteSetting->getAttributes());
    }

    private function submit(SiteSetting $siteSetting): TestResponse
    {
        $user = $this->createSuperUser();

        $url = route('admin.site-settings.destroy', $siteSetting);

        return $this->actingAs($user)->delete($url);
    }
}

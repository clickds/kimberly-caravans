<?php

namespace Tests\Feature\Admin\Sites;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Site;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $site = $this->createSite();

        $response = $this->submit($site);

        $response->assertRedirect(route('admin.sites.index'));
        $this->assertDatabaseMissing('sites', [
            'id' => $site->id,
        ]);
    }

    private function submit(Site $site)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($site);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Site $site)
    {
        return route('admin.sites.destroy', $site);
    }
}

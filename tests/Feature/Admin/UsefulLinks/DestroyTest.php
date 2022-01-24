<?php

namespace Tests\Feature\Admin\UsefulLinks;

use App\Models\UsefulLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroys_useful_link()
    {
        $usefulLink = factory(UsefulLink::class)->create();

        $response = $this->submit($usefulLink);

        $response->assertRedirect(route('admin.useful-links.index'));
        $this->assertDatabaseMissing('useful_links', $usefulLink->getAttributes());
    }

    private function submit(UsefulLink $usefulLink): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.useful-links.destroy', $usefulLink);

        return $this->actingAs($user)->delete($url);
    }
}

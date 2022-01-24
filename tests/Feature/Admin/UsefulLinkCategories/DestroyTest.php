<?php

namespace Tests\Feature\Admin\UsefulLinkCategories;

use App\Models\UsefulLinkCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_deletes_useful_link_category(): void
    {
        $usefulLinkCategory = factory(UsefulLinkCategory::class)->create();

        $response = $this->submit($usefulLinkCategory);

        $response->assertRedirect(route('admin.useful-link-categories.index'));
        $this->assertDatabaseMissing('useful_link_categories', $usefulLinkCategory->getAttributes());
    }

    private function submit(UsefulLinkCategory $usefulLinkCategory): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.useful-link-categories.destroy', $usefulLinkCategory);

        return $this->actingAs($user)->delete($url);
    }
}

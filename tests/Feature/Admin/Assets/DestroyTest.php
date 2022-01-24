<?php

namespace Tests\Feature\Admin\Assets;

use App\Models\WysiwygUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_deletes_wysiwyg_upload()
    {
        $asset = factory(WysiwygUpload::class)->create();

        $response = $this->submit($asset);

        $response->assertRedirect(route('admin.assets.index'));
    }

    private function submit(WysiwygUpload $wysiwygUpload): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.assets.destroy', $wysiwygUpload);

        return $this->actingAs($user)->delete($url);
    }
}

<?php

namespace Tests\Feature\Admin\Buttonable\ImageBannerButtons;

use App\Models\Button;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroying_button(): void
    {
        $button = $this->createButton();

        $response = $this->submit($button);

        $response->assertRedirect(route('admin.image-banners.buttons.index', $button->buttonable));
        $this->assertDatabaseMissing('buttons', $button->getAttributes());
    }

    private function submit($button): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.image-banners.buttons.destroy', [
            'buttonable' => $button->buttonable,
            'button' => $button,
        ]);

        return $this->actingAs($user)->delete($url);
    }

    private function createButton(array $attributes = []): Button
    {
        return factory(Button::class)->state('image-banner')->create($attributes);
    }
}

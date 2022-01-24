<?php

namespace Tests\Feature\Admin\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Form;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $form = factory(Form::class)->create();

        $response = $this->submit($form);

        $response->assertRedirect($this->redirectUrl());
        $this->assertDatabaseMissing('forms', $form->getAttributes());
    }

    private function submit(Form $form)
    {
        $user = $this->createSuperUser();
        $url = $this->url($form);
        return $this->actingAs($user)->delete($url);
    }

    private function url(Form $form): string
    {
        return route('admin.forms.destroy', $form);
    }

    private function redirectUrl(): string
    {
        return route('admin.forms.index');
    }
}

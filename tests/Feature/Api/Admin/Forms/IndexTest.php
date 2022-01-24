<?php

namespace Tests\Feature\Api\Admin\Forms;

use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $forms = factory(Form::class, 10)->create();

        $response = $this->submit();

        $response->assertStatus(200);
        foreach ($forms as $form) {
            $response->assertJsonFragment([
                'id' => $form->id,
                'name' => $form->name,
            ]);
        }
    }

    private function submit()
    {
        $user = $this->createSuperUser();
        $url = $this->url();
        return $this->actingAs($user)->get($url);
    }

    private function url()
    {
        return route('api.admin.forms.index');
    }
}

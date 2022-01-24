<?php

namespace Tests\Feature\Admin\Form\Clones;

use App\Models\Fieldset;
use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_clones_form(): void
    {
        $oldForm = factory(Form::class)->create();

        $response = $this->submit($oldForm);

        $response->assertRedirect();

        $latestForm = Form::where('id', '!=', $oldForm->id)->first();

        $this->assertEquals(
            Arr::except($oldForm->getAttributes(), ['id', 'created_at', 'updated_at', 'name']),
            Arr::except($latestForm->getAttributes(), ['id', 'created_at', 'updated_at', 'name']),
        );

        $this->assertEquals(
            sprintf('%s Clone', $oldForm->name),
            $latestForm->name
        );
    }

    public function test_clones_form_and_fieldset_associations(): void
    {
        $oldForm = factory(Form::class)->create();
        $fieldset = factory(Fieldset::class)->create();
        $oldForm->fieldsets()->attach($fieldset);

        $response = $this->submit($oldForm);

        $response->assertRedirect();

        $latestForm = Form::latest()->first();
        $this->assertDatabaseHas('fieldset_form', [
            'fieldset_id' => $fieldset->id,
            'form_id' => $latestForm->id,
        ]);
    }

    private function submit(Form $form): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.forms.clones.store', $form);

        return $this->actingAs($user)->post($url);
    }
}

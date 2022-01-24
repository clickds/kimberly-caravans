<?php

namespace Tests\Feature\Admin\Forms;

use App\Models\Fieldset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Form;

class StoreTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

        /**
     * @dataProvider required_fields_provider
     */
    public function test_required_fileds(string $requiredFieldName)
    {
        $data = $this->validFields([
            $requiredFieldName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function required_fields_provider(): array
    {
        return [
            ['name'],
            ['email_to'],
            ['type'],
            ['successful_submission_message'],
        ];
    }

    public function test_name_is_unique(): void
    {
        $otherForm = factory(Form::class)->create();
        $data = $this->validFields([
            'name' => $otherForm->name,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_email_to_is_an_email(): void
    {
        $data = $this->validFields([
            'email_to' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('email_to');
    }

    public function test_successful(): void
    {
        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect($this->redirectUrl());
        $this->assertDatabaseHas('forms', $data);
    }

    public function test_syncs_fieldsets(): void
    {
        $formData = $this->validFields();
        $fieldset = factory(Fieldset::class)->create();
        $secondFieldset = factory(Fieldset::class)->create();
        $data = $formData;
        $data['fieldset_ids'][] = $fieldset->id;
        $data['fieldset_ids'][] = $secondFieldset->id;

        $response = $this->submit($data);

        $response->assertRedirect($this->redirectUrl());
        $this->assertDatabaseHas('forms', $formData);
        $this->assertDatabaseHas('fieldset_form', [
            'fieldset_id' => $fieldset->id,
            'position' => 0,
        ]);
        $this->assertDatabaseHas('fieldset_form', [
            'fieldset_id' => $secondFieldset->id,
            'position' => 1,
        ]);
    }

    private function submit(array $data)
    {
        $user = $this->createSuperUser();
        $url = $this->url();
        return $this->actingAs($user)->post($url, $data);
    }

    private function validFields(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(Form::VALID_TYPES),
            'email_to' => $this->faker->email,
            'successful_submission_message' => 'Thanks for your input',
            'crm_list' => null,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(): string
    {
        return route('admin.forms.store');
    }

    private function redirectUrl(): string
    {
        return route('admin.forms.index');
    }
}

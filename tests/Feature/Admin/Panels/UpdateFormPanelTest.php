<?php

namespace Tests\Feature\Admin\Panels;

use App\Models\Form;
use App\Models\Panel;

class UpdateFormPanelTest extends BaseUpdatePanelTestCase
{
    /**
     * @dataProvider required_fields_provider
     */
    public function test_required_fields(string $requiredFieldName): void
    {
        $data = $this->valid_fields([$requiredFieldName => null]);
        $response = $this->submit($data);
        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function required_fields_provider(): array
    {
        return array_merge(
            $this->default_required_fields(),
            [
                ['featureable_id'],
                ['featureable_type'],
            ]
        );
    }

    public function test_successful(): void
    {
        $data = $this->valid_fields();
        $response = $this->submit($data);
        $response->assertRedirect($this->redirect_url());
        $this->assertDatabaseHas('panels', $data);
    }

    protected function valid_fields(array $overrides = []): array
    {
        $form = factory(Form::class)->create();

        return array_merge(
            $this->default_valid_fields(),
            [
                'type' => Panel::TYPE_FORM,
                'featureable_id' => $form->id,
                'featureable_type' => Form::class,
            ],
            $overrides
        );
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\Fieldset;
use App\Models\Field;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = factory(Form::class)->create();
        $fieldsets = factory(Fieldset::class, 2)->create([
            'form_id' => $form->id,
        ]);
        foreach ($fieldsets as $fieldset) {
            $this->createFields($fieldset);
        }
    }

    private function createFields(Fieldset $fieldset)
    {
        foreach (array_keys(Field::TYPES) as $type) {
            $options = null;
            if (in_array($type, Field::TYPES_REQUIRING_OPTIONS)) {
                $options = ['option a', 'option b', 'option c'];
            }

            factory(Field::class)->create([
                'fieldset_id' => $fieldset->id,
                'options' => $options,
            ]);
        }
    }
}

<?php

namespace App\Services\FormBuilder;

use App\Models\Form;
use App\Models\Field;
use Illuminate\Database\Eloquent\Collection;
use App\Services\FormBuilder\FieldValidationRules\BaseGenerator as FieldValidationRuleGenerator;

class ValidationRulesGenerator
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\Field>
     */
    private $fields;

    public function __construct(Form $form)
    {
        $this->fields = $this->fetchFields($form);
    }

    public function call(): array
    {
        $rules = [];

        foreach ($this->getFields() as $field) {
            $fieldRules = $this->validationRulesForField($field);
            $rules = array_merge($rules, $fieldRules);
        }

        return $rules;
    }

    private function validationRulesForField(Field $field): array
    {
        $generator = FieldValidationRuleGenerator::for($field);
        return $generator->call();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Field>
     */
    private function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Field>
     */
    private function fetchFields(Form $form): Collection
    {
        $fieldsetIds = $form->fieldsets()->toBase()->pluck('id');
        return Field::whereIn('fieldset_id', $fieldsetIds)->get();
    }
}

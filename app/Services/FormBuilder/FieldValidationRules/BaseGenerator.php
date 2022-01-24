<?php

namespace App\Services\FormBuilder\FieldValidationRules;

use App\Models\Field;

abstract class BaseGenerator
{
    /**
     * @var \App\Models\Field $field
     */
    private $field;

    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    protected function getField(): Field
    {
        return $this->field;
    }

    protected function inputName(): string
    {
        return $this->getField()->input_name;
    }

    protected function options(): array
    {
        return $this->getField()->options;
    }

    protected function isRequired(): bool
    {
        return $this->getField()->required;
    }

    abstract protected function call(): array;

    /**
     * @return mixed
     */
    public static function for(Field $field)
    {
        switch ($field->type) {
            case Field::TYPE_CHECKBOX:
                return new CheckboxGenerator($field);
            case Field::TYPE_EMAIL:
                return new EmailGenerator($field);
            case Field::TYPE_MULTIPLE_CHECKBOXES:
                return new MultipleCheckboxesGenerator($field);
            case Field::TYPE_RADIO_BUTTONS:
                return new RadioButtonsGenerator($field);
            case Field::TYPE_SELECT:
                return new SelectGenerator($field);
            case Field::TYPE_TEXTAREA:
                return new TextAreaGenerator($field);
            case Field::TYPE_TEXT:
                return new TextGenerator($field);
            case Field::TYPE_CAPTCHA:
                return new CaptchaGenerator($field);
            case Field::TYPE_DEALER_SELECT:
                return new DealerSelectGenerator($field);
            case Field::TYPE_DEALER_CHECKBOXES:
                return new DealerCheckboxesGenerator($field);
            case Field::TYPE_BUSINESS_AREA_SELECT:
                return new BusinessAreaSelectGenerator($field);
            case Field::TYPE_FILE_UPLOAD:
                return new FileUploadGenerator($field);
            default:
                return new NoValidationGenerator($field);
        }
    }
}

<?php

namespace App\Presenters\PanelType;

use App\Models\Fieldset;
use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;

class FormPresenter extends BasePanelPresenter
{
    public function getFormSubmissionUrl(): string
    {
        return route('form-submissions', $this->getForm());
    }

    public function getForm(): Form
    {
        $featureable = $this->getPanel()->featureable;
        if ($featureable instanceof Form) {
            return $featureable;
        }
        return new Form();
    }

    public function getFieldsets(): Collection
    {
        return $this->getForm()->fieldsets;
    }

    public function getFields(Fieldset $fieldset): Collection
    {
        return $fieldset->fields;
    }
}

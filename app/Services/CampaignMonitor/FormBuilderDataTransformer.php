<?php

namespace App\Services\CampaignMonitor;

use App\Models\Field;
use App\Models\Form;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class FormBuilderDataTransformer
{
    private Collection $fields;
    private Collection $crmSubmissionData;

    public function __construct(Form $form, array $submissionData)
    {
        $this->fields = $this->fetchFields($form);
        $this->crmSubmissionData = $this->filterData($submissionData);
    }

    public function call(): array
    {
        $crmData = [
            Field::CRM_FIELD_EMAIL => $this->fetchEmailAddress(),
            Field::CRM_FIELD_NAME => $this->fetchName(),
            'Resubscribe' => true,
            'ConsentToTrack' => 'yes',
        ];
        $customFields = [];

        foreach ($this->customFieldData() as $crmFieldName => $submissionValue) {
            if ($crmFieldName === Field::CRM_FIELD_FILE_UPLOAD) {
                $customFields[] = [
                    'Key' => $crmFieldName,
                    'Value' => $submissionValue instanceof UploadedFile
                        ? $submissionValue->getClientOriginalName()
                        : 'N/A',
                ];
                continue;
            }

            $values = (array) $submissionValue;

            foreach ($values as $value) {
                if ($crmFieldName === Field::CRM_FIELD_CONTACT_PREFERENCES) {
                    $value = $this->transformValueForContactPreferences($value);
                }

                $customFields[] = [
                    'Key' => $crmFieldName,
                    'Value' => $value,
                ];
            }
        }

        $crmData['CustomFields'] = $customFields;

        return $crmData;
    }

    private function transformValueForContactPreferences(string $value): string
    {
        $value = strtolower($value);
        if (strpos($value, 'email') !== false) {
            return 'Email - Please confirm details';
        }
        if (strpos($value, 'post') !== false) {
            return 'Post - Please confirm details';
        }
        if (strpos($value, 'phone') !== false) {
            return 'Telephone - Please confirm details';
        }
        return "Share with third parties - we will only pass on your details to carefully selected third parties";
    }

    private function customFieldData(): Collection
    {
        return $this->crmSubmissionData->reject(function ($submissionValue, $crmFieldName) {
            return in_array($crmFieldName, [Field::CRM_FIELD_NAME, Field::CRM_FIELD_EMAIL]);
        });
    }

    private function fetchEmailAddress(): ?string
    {
        return $this->crmSubmissionData->last(function ($submissionValue, $crmFieldName) {
            return $crmFieldName === Field::CRM_FIELD_EMAIL;
        });
    }

    private function fetchName(): ?string
    {
        return $this->crmSubmissionData->last(function ($value, $crmFieldName) {
            return $crmFieldName === Field::CRM_FIELD_NAME;
        });
    }


    private function filterData(array $submissionData): Collection
    {
        $filteredData = collect();
        foreach ($submissionData as $inputName => $submissionValue) {
            $crmField = $this->getFieldByInputName((string) $inputName);
            if (is_null($crmField)) {
                continue;
            }
            $filteredData->put($crmField->crm_field_name, $submissionValue);
        }
        return $filteredData;
    }

    private function getFieldByInputName(string $inputName): ?Field
    {
        return $this->fields->where('input_name', $inputName)->first();
    }

    /**
     * @return Collection<\App\Models\Field>
     */
    private function fetchFields(Form $form): Collection
    {
        $fieldsetIds = $form->fieldsets()->toBase()->pluck('id');
        return Field::whereIn('fieldset_id', $fieldsetIds)->whereNotNull('crm_field_name')
            ->orderBy('position', 'asc')->get();
    }
}

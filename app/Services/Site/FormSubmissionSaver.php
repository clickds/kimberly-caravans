<?php

namespace App\Services\Site;

use App\Models\Field;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

final class FormSubmissionSaver
{
    public function save(Form $form, array $submissionData): FormSubmission
    {
        $fields = $this->fetchFields($form);
        $formattedSubmissionData = $this->replaceInputNamesWithActualNames($fields, $submissionData);
        $submission = new FormSubmission(['submission_data' => $formattedSubmissionData]);
        $submission = $form->submissions()->save($submission);
        $this->saveUploadedFiles($submission, $this->fetchFields($form), $submissionData);

        return $submission;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection<\App\Models\Field> $fields
     */
    private function replaceInputNamesWithActualNames(Collection $fields, array $submissionData): array
    {
        $formattedSubmissionData = [];

        foreach ($submissionData as $inputName => $data) {
            $field = $fields->where('input_name', $inputName)->first();

            if (is_null($field)) {
                $formattedSubmissionData[$inputName] = $data;
                continue;
            }

            switch ($field->type) {
                case Field::TYPE_CAPTCHA:
                    break;
                case Field::TYPE_FILE_UPLOAD:
                    $formattedSubmissionData[$field->label] = $data instanceof UploadedFile
                        ? $data->getClientOriginalName()
                        : 'N/A';
                    break;
                default:
                    $formattedSubmissionData[$field->label] = $data;
            }
        }

        return $formattedSubmissionData;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Field>
     */
    private function fetchFields(Form $form): Collection
    {
        $fieldsetIds = $form->fieldsets()->toBase()->pluck('id');
        return Field::whereIn('fieldset_id', $fieldsetIds)->get();
    }

    private function saveUploadedFiles(FormSubmission $submission, Collection $fields, array $submissionData): void
    {
        $fileUploadFields = $fields->where('type', Field::TYPE_FILE_UPLOAD);

        foreach ($fileUploadFields as $fileUploadField) {
            $submission->addMedia($submissionData[$fileUploadField->input_name])->toMediaCollection('file-uploads');
        }
    }
}

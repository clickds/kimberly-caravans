<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVacancyApplicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
            ],
            'first_name' => [
                'required',
                'string',
            ],
            'last_name' => [
                'required',
                'string',
            ],
            'address' => [
                'required',
                'string',
            ],
            'nationality' => [
                'required',
                'string',
            ],
            'require_permission_to_work_in_uk' => [
                'required',
                'boolean',
            ],
            'number_of_dependents' => [
                'required',
                'numeric',
            ],
            'telephone_number' => [
                'required',
                'string',
            ],
            'mobile_number' => [
                'nullable',
                'string',
            ],
            'have_own_transport' => [
                'required',
                'boolean',
            ],
            'currently_employed_by' => [
                'nullable',
                'string',
            ],
            'current_position' => [
                'nullable',
                'string',
            ],
            'seeking_employment_change_reason' => [
                'nullable',
                'string',
            ],
            'weeks_notice_required' => [
                'nullable',
                'numeric',
            ],
            'conviction_details' => [
                'nullable',
                'string',
            ],
            'marquis_employee_reference_name' => [
                'nullable',
                'string',
            ],
            'booked_holiday_details' => [
                'nullable',
                'string',
            ],
            'have_any_disabilities' => [
                'required',
                'boolean',
            ],
            'disability_details' => [
                'nullable',
                'string',
            ],
            'wear_glasses_or_contacts' => [
                'required',
                'boolean',
            ],
            'glasses_or_contacts_details' => [
                'nullable',
                'string',
            ],
            'receiving_medical_treatment' => [
                'required',
                'boolean',
            ],
            'medical_treatment_details' => [
                'nullable',
                'string',
            ],
            'smoker' => [
                'required',
                'boolean',
            ],
            'courses_and_certificates' => [
                'nullable',
                'string',
            ],
            'practical_experience' => [
                'nullable',
                'string',
            ],
            'hobbies_and_interests' => [
                'nullable',
                'string',
            ],
            'references' => [
                'nullable',
                'string',
            ],
            'employment_history' => [
                'required',
                'array',
            ],
            'employment_history.*.position' => [
                'required',
                'string',
            ],
            'employment_history.*.start_date' => [
                'required',
                'date',
            ],
            'employment_history.*.end_date' => [
                'nullable',
                'date',
            ],
            'employment_history.*.employer_details' => [
                'required',
                'string',
            ],
            'employment_history.*.reasons_for_leaving' => [
                'required',
                'string',
            ],
            'dealer_id' => [
                'nullable',
                Rule::exists('dealers', 'id'),
            ],
            recaptchaFieldName() => recaptchaRuleName(),
        ];
    }
}

<?php

namespace App\Http\Requests\Site;

use App\Models\Form;
use App\Services\FormBuilder\ValidationRulesGenerator;
use Illuminate\Foundation\Http\FormRequest;

class FormSubmissionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $form = $this->form();
        if (is_null($form)) {
            abort(404);
        }
        $rulesGenerator = new ValidationRulesGenerator($form);
        return $rulesGenerator->call();
    }

    private function form(): ?Form
    {
        if ($form = $this->route('form')) {
            return $form;
        }
        return null;
    }
}

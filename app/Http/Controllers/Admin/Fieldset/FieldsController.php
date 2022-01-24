<?php

namespace App\Http\Controllers\Admin\Fieldset;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\Fieldset\Fields\StoreRequest;
use App\Http\Requests\Admin\Fieldset\Fields\UpdateRequest;
use App\Models\Fieldset;
use App\Models\Field;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FieldsController extends BaseController
{
    public function index(Fieldset $fieldset): View
    {
        $fields = $fieldset->fields()->orderBy('position', 'asc')->get();

        return view('admin.fieldset.fields.index', [
            'fieldset' => $fieldset,
            'fields' => $fields,
        ]);
    }

    public function create(Fieldset $fieldset): View
    {
        $field = $fieldset->fields()->make();

        return view('admin.fieldset.fields.create', [
            'crmFieldNames' => $this->getCrmFieldNames(),
            'fieldset' => $fieldset,
            'field' => $field,
            'types' => $this->getTypes(),
            'typesWithOptions' => $this->getTypesWithOptions(),
            'widths' => $this->getWidths(),
        ]);
    }

    public function store(StoreRequest $request, Fieldset $fieldset): RedirectResponse
    {
        $data = $request->validated();
        $fieldset->fields()->create($data);

        return redirect()
            ->route('admin.fieldsets.fields.index', $fieldset)
            ->with('success', 'Field created');
    }

    public function edit(Fieldset $fieldset, Field $field): View
    {
        return view('admin.fieldset.fields.edit', [
            'crmFieldNames' => $this->getCrmFieldNames(),
            'fieldset' => $fieldset,
            'field' => $field,
            'types' => $this->getTypes(),
            'typesWithOptions' => $this->getTypesWithOptions(),
            'widths' => $this->getWidths(),
        ]);
    }

    public function update(UpdateRequest $request, Fieldset $fieldset, Field $field): RedirectResponse
    {
        $data = $request->validated();
        $field->update($data);

        return redirect()
            ->route('admin.fieldsets.fields.index', $fieldset)
            ->with('success', 'Field updated');
    }

    public function destroy(Fieldset $fieldset, Field $field): RedirectResponse
    {
        $field->delete();

        return redirect()
            ->route('admin.fieldsets.fields.index', $fieldset)
            ->with('success', 'Field deleted');
    }

    private function getTypes(): array
    {
        return Field::TYPES;
    }

    private function getWidths(): array
    {
        return Field::WIDTHS;
    }

    private function getTypesWithOptions(): array
    {
        return Field::TYPES_REQUIRING_OPTIONS;
    }

    private function getCrmFieldNames(): array
    {
        return Field::CRM_FIELD_NAMES;
    }
}

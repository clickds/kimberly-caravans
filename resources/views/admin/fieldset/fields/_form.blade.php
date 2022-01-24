<form method="POST" action="{{ $url }}" class="admin-form">
  @include('admin._partials.errors')

  @if ($field->exists)
    @method('put')
  @endif
  @csrf

  <form-fields
    :crm-field-names='@json($crmFieldNames)'
    initial-crm-field-name="{{ old('crm_field_name', $field->crm_field_name) }}"
    initial-name="{{ old('name', $field->name) }}"
    initial-label="{{ old('label', $field->label) }}"
    :initial-position="{{ old('position', $field->position) }}"
    :initial-required="{{ old('required', $field->required) == 1 ? 'true' : 'false' }}"
    initial-type="{{ old('type', $field->type) }}"
    initial-width="{{ old('width', $field->width) }}"
    :initial-options='@json($field->options)'
    :types='@json($types)'
    :types-with-options='@json($typesWithOptions)'
    :widths='@json($widths)'
  ></form-fields>


  <div class="flex items-center justify-between">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      @if ($field->exists)
        @lang('global.update')
      @else
        @lang('global.create')
      @endif
    </button>
  </div>
</form>
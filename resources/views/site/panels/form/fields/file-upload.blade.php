<div class="{{ $field->cssClasses() }}">
  <label>{{ $field->required ? '* ' : '' }}{{ $field->label }}</label>
  <input
      type="file"
      name="{{ $field->input_name }}"
      value="{{ old($field->input_name, '') }}"
      {{ $field->required ? 'required' : '' }} />
</div>
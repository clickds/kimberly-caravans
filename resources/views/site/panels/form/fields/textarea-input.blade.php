<div class="{{ $field->cssClasses() }}">
  <label>{{ $field->required ? '* ' : '' }}{{ $field->label }}</label>
  <textarea
      name="{{ $field->input_name }}"
      {{ $field->required ? 'required' : '' }}>{{ old($field->input_name, '') }}</textarea>
</div>
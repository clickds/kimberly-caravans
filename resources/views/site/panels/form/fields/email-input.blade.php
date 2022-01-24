<div class="{{ $field->cssClasses() }}">
  <label for="{{ $field->input_name }}">{{ $field->required ? '* ' : '' }}{{ $field->label }}</label>
  <input
      type="email"
      name="{{ $field->input_name }}"
      value="{{ old($field->input_name, '') }}"
      {{ $field->required ? 'required' : '' }}
  />
</div>
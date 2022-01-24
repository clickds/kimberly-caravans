<div class="{{ $field->cssClasses() }}">
  <label>{{ $field->required ? '* ' : '' }}{{ $field->label }}</label>
  <select
      name="{{ $field->input_name }}"
      {{ $field->required ? 'required' : '' }}
  >
    @foreach($field->getOptions($site) as $option)
      <option value="{{ $option }}" {{ $option == old($field->input_name) ? 'selected' : '' }}>{{ $option }}</option>
    @endforeach
  </select>
</div>

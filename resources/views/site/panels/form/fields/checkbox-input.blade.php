<div class="{{ $field->cssClasses() }}">
  <label for="{{ $field->input_name }}" class="flex flex-row items-center cursor-pointer">
    <input
        type="checkbox"
        name="{{ $field->input_name }}"
        id="{{ $field->input_name }}"
        value="1"
        {{ "1" == old($field->input_name) ? 'checked' : '' }}
        {{ $field->required ? ' required' : '' }}
    >
    {{ $field->required ? '* ' : '' }}{{ $field->label }}
  </label>
</div>

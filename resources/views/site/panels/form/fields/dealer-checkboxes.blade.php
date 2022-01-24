<div class="{{ $field->cssClasses() }}">
  <fieldset class="w-full field-fieldset">
    <legend>{{ $field->required ? '* ' : '' }}{{ $field->label }}</legend>

    <div class="flex flex-wrap">
      @foreach($field->getOptions($site) as $option)
        <label for="{{ $field->input_name . $loop->index }}" class="w-full md:w-1/2 lg:w-1/3 flex flex-row items-start cursor-pointer mb-2">
          <input
            type="checkbox"
            class="mt-1"
            name="{{ $field->input_name }}[]"
            id="{{ $field->input_name . $loop->index }}"
            value="{{ $option }}"
            {{ in_array($option, old($field->input_name, [])) ? 'checked' : ''}}
          />
          <div>{{ $option }}</div>
        </label>
      @endforeach
    </div>
  </fieldset>
</div>
<div class="{{ $field->cssClasses() }}">
  <fieldset class="w-full field-fieldset">
    <legend>{{ $field->label }}</legend>
    <div class="flex flex-wrap">
      @foreach($field->getOptions($site) as $option)
        <div class="w-full md:w-1/2 lg:w-1/3 flex flex-row items-start mb-2">
          <input
              type="radio"
              name="{{ $field->input_name }}"
              class="mt-1"
              id="{{ $field->input_name . $loop->index }}"
              value="{{ $option }}"
              {{ $field->required ? 'required' : '' }}
              {{ $option == old($field->input_name, '') ? 'checked' : '' }}
          />
          <label for="{{ $field->input_name . $loop->index }}">
            {{ $field->required ? '* ' : '' }}{{ $option }}
          </label>
        </div>
      @endforeach
    </div>
  </fieldset>
</div>
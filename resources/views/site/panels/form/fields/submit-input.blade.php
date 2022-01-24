<div class="{{ $field->cssClasses() }}">
  <input type="submit" value="{{ $field->label }}" name="{{ $field->input_name }}" {{ $field->required ? 'required' : '' }} />
</div>

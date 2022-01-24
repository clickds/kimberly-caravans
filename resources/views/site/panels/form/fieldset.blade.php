<fieldset name="{{ $fieldset->name }}" class="flex flex-col">
  <legend>{{ $fieldset->name }}</legend>

  <div class="content-container">
    <div class="w-full mb-5">
      {!! $fieldset->content !!}
    </div>

    <div class="fields-container">
      @foreach($fieldset->fields as $field)
        @switch($field->type)
          @case(\App\Models\Field::TYPE_CAPTCHA)
            @include('site.panels.form.fields.captcha-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_TEXT)
            @include('site.panels.form.fields.text-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_CHECKBOX)
            @include('site.panels.form.fields.checkbox-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_EMAIL)
            @include('site.panels.form.fields.email-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_MULTIPLE_CHECKBOXES)
            @include('site.panels.form.fields.multiple-checkbox-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_RADIO_BUTTONS)
            @include('site.panels.form.fields.radio-buttons-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_SELECT)
            @include('site.panels.form.fields.select-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_TEXTAREA)
            @include('site.panels.form.fields.textarea-input', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_DEALER_SELECT)
            @include('site.panels.form.fields.dealer-select', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_DEALER_CHECKBOXES)
            @include('site.panels.form.fields.dealer-checkboxes', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_BUSINESS_AREA_SELECT)
            @include('site.panels.form.fields.business-area-select', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_FILE_UPLOAD)
            @include('site.panels.form.fields.file-upload', ['field' => $field])
            @break
          @case(\App\Models\Field::TYPE_SUBMIT)
            @include('site.panels.form.fields.submit-input', ['field' => $field])
            @break
        @endswitch
      @endforeach
    </div>
  </div>
</fieldset>

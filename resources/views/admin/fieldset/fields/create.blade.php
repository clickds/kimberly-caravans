@extends('layouts.admin')

@section('title', 'Create Field')

@section('page-title', 'Create Field')

@section('page')
  <div>
    @include('admin.fieldset.fields._form', [
      'crmFieldNames' => $crmFieldNames,
      'field' => $field,
      'types' => $types,
      'typesWithOptions' => $typesWithOptions,
      'widths' => $widths,
      'url' => route('admin.fieldsets.fields.store', [
        'fieldset' => $fieldset,
      ]),
    ])
  </div>
@endsection
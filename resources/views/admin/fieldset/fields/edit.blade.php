@extends('layouts.admin')

@section('title', 'Edit Fieldset')

@section('page-title', 'Edit Fieldset')

@section('page')
  <div>
    @include('admin.fieldset.fields._form', [
      'crmFieldNames' => $crmFieldNames,
      'field' => $field,
      'types' => $types,
      'typesWithOptions' => $typesWithOptions,
      'widths' => $widths,
      'url' => route('admin.fieldsets.fields.update', [
        'field' => $field,
        'fieldset' => $fieldset,
      ]),
    ])
  </div>
@endsection
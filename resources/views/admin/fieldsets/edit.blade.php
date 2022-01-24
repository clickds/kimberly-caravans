@extends('layouts.admin')

@section('title', 'Edit Fieldset')

@section('page-title', 'Edit Fieldset')

@section('page')
  <div>
    @include('admin.fieldsets._form', [
      'fieldset' => $fieldset,
      'url' => route('admin.fieldsets.update', $fieldset)
    ])
  </div>
@endsection
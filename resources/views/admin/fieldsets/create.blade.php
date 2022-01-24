@extends('layouts.admin')

@section('title', 'Create Fieldset')

@section('page-title', 'Create Fieldset')

@section('page')
  <div>
    @include('admin.fieldsets._form', [
      'fieldset' => $fieldset,
      'url' => route('admin.fieldsets.store')
    ])
  </div>
@endsection
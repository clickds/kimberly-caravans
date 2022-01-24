@extends('layouts.admin')

@section('title', 'Edit Form')

@section('page-title', 'Edit Form')

@section('page')
  <div>
    @include('admin.forms._form', [
      'emailRecipients' => $emailRecipients,
      'fieldsets' => $fieldsets,
      'form' => $form,
      'url' => route('admin.forms.update', [
        'form' => $form,
      ]),
    ])
  </div>
@endsection
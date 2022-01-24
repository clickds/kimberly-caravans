@extends('layouts.admin')

@section('title', 'Create Form')

@section('page-title', 'Create Form')

@section('page')
  <div>
    @include('admin.forms._form', [
      'email_recipients' => $emailRecipients,
      'fieldsets' => $fieldsets,
      'form' => $form,
      'url' => route('admin.forms.store'),
    ])
  </div>
@endsection
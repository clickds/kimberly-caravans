@extends('layouts.admin')

@section('title', 'Create Email Recipient')

@section('page-title', 'Create Email Recipient')

@section('page')
  <div>
    @include('admin.email-recipients._form', [
      'emailRecipient' => $emailRecipient,
      'url' => route('admin.email-recipients.store')
    ])
  </div>
@endsection
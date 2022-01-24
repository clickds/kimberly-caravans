@extends('layouts.admin')

@section('title', 'Edit Email Recipient')

@section('page-title', 'Edit Email Recipient')

@section('page')
  <div>
    @include('admin.email-recipients._form', [
      'emailRecipient' => $emailRecipient,
      'url' => route('admin.email-recipients.update', $emailRecipient)
    ])
  </div>
@endsection
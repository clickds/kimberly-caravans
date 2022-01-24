@extends('layouts.admin')

@section('title', 'Edit Business Area')

@section('page-title', 'Edit Email Recipient')

@section('page')
  <div>
    @include('admin.business-areas._form', [
      'emailRecipient' => $businessArea,
      'url' => route('admin.business-areas.update', $businessArea)
    ])
  </div>
@endsection
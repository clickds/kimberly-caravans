@extends('layouts.admin')

@section('title', 'Create Business Area')

@section('page-title', 'Create Business Area')

@section('page')
  <div>
    @include('admin.business-areas._form', [
      'businessArea' => $businessArea,
      'url' => route('admin.business-areas.store')
    ])
  </div>
@endsection
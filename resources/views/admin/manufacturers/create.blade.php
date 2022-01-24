@extends('layouts.admin')

@section('title', 'Create Manufacturer')
@section('page-title', 'Create Manufacturer')

@section('page')
  <div class="w-full">
    @include('admin.manufacturers._form', [
      'manufacturer' => $manufacturer,
      'url' => route('admin.manufacturers.store')
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Edit Manufacturer')

@section('page-title', 'Edit Manufacturer')

@section('page')
  <div class="w-full">
    @include('admin.manufacturers._form', [
      'manufacturer' => $manufacturer,
      'url' => route('admin.manufacturers.update', $manufacturer)
    ])
  </div>
@endsection
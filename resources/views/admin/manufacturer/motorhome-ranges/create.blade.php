@extends('layouts.admin')

@section('title', 'Create Motorhome Range')

@section('page-title', 'Create Motorhome Range')

@section('page')
  <div class="w1/2">
    @include('admin.manufacturer.motorhome-ranges._form', [
      'url' => route('admin.manufacturers.motorhome-ranges.store', $manufacturer),
      'motorhomeRange' => $motorhomeRange,
      'sites' => $sites,
    ])
  </div>
@endsection
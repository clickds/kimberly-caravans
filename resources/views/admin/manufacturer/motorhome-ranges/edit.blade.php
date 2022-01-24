@extends('layouts.admin')

@section('title', 'Edit Motorhome Range')

@section('page-title', 'Edit Motorhome Range')

@section('page')
  <div class="w1/2">
    @include('admin.manufacturer.motorhome-ranges._form', [
      'url' => route('admin.manufacturers.motorhome-ranges.update', [
        'manufacturer' => $manufacturer,
        'motorhome_range' => $motorhomeRange,
      ]),
      'motorhomeRange' => $motorhomeRange,
      'sites' => $sites,
    ])
  </div>
@endsection
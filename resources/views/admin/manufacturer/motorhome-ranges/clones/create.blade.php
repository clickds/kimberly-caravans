@extends('layouts.admin')

@section('title', 'Clone Motorhome Range')

@section('page-title', 'Clone Motorhome Range')

@section('page')
  <div class="w1/2">
    @include('admin.manufacturer.motorhome-ranges.clones._form', [
      'url' => route('admin.manufacturers.motorhome-ranges.clones.store', [
        'manufacturer' => $manufacturer,
        'motorhome_range' => $motorhomeRange
      ]),
      'motorhomeRange' => $motorhomeRange,
      'sites' => $sites,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Clone Caravan Range')

@section('page-title', 'Clone Caravan Range')

@section('page')
  <div class="w1/2">
    @include('admin.manufacturer.caravan-ranges.clones._form', [
      'url' => route('admin.manufacturers.caravan-ranges.clones.store', [
        'manufacturer' => $manufacturer,
        'caravan_range' => $caravanRange
      ]),
      'caravanRange' => $caravanRange,
      'sites' => $sites,
    ])
  </div>
@endsection
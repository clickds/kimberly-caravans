@extends('layouts.admin')

@section('title', 'Edit Caravan Range')

@section('page-title', 'Edit Caravan Range')

@section('page')
  <div class="w1/2">
    @include('admin.manufacturer.caravan-ranges._form', [
      'url' => route('admin.manufacturers.caravan-ranges.update', [
        'manufacturer' => $manufacturer,
        'caravan_range' => $caravanRange,
      ]),
      'caravanRange' => $caravanRange,
      'sites' => $sites,
    ])
  </div>
@endsection
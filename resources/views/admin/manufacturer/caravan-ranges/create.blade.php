@extends('layouts.admin')

@section('title', 'Create Caravan Range')

@section('page-title', 'Create Caravan Range')

@section('page')
  <div class="w1/2">
    @include('admin.manufacturer.caravan-ranges._form', [
      'url' => route('admin.manufacturers.caravan-ranges.store', $manufacturer),
      'caravanRange' => $caravanRange,
      'sites' => $sites,
    ])
  </div>
@endsection
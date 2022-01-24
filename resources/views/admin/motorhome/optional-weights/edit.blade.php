@extends('layouts.admin')

@section('title', 'Edit Optional Weight')

@section('page-title', 'Edit Optional Weight')

@section('page')
  <div class="w1/2">
    @include('admin.motorhome.optional-weights._form', [
      'url' => route('admin.motorhomes.optional-weights.update', [
        'optional_weight' => $optionalWeight,
        'motorhome' => $motorhome,
      ]),
      'optionalWeight' => $optionalWeight,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Optional Weight')

@section('page-title', 'Create Optional Weight')

@section('page')
  <div class="w1/2">
    @include('admin.motorhome.optional-weights._form', [
      'url' => route('admin.motorhomes.optional-weights.store', $motorhome),
      'optionalWeight' => $optionalWeight,
    ])
  </div>
@endsection
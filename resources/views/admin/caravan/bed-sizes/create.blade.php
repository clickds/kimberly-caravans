@extends('layouts.admin')

@section('title', 'Create Bed Size')

@section('page-title', 'Create Bed Size')

@section('page')
  <div class="w1/2">
    @include('admin.caravan.bed-sizes._form', [
      'url' => route('admin.caravans.bed-sizes.store', $caravan),
      'bedDescriptions' => $bedDescriptions,
      'bedSize' => $bedSize,
    ])
  </div>
@endsection
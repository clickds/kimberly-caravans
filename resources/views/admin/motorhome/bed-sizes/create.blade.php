@extends('layouts.admin')

@section('title', 'Create Bed Size')

@section('page-title', 'Create Bed Size')

@section('page')
  <div class="w1/2">
    @include('admin.motorhome.bed-sizes._form', [
      'url' => route('admin.motorhomes.bed-sizes.store', $motorhome),
      'bedDescriptions' => $bedDescriptions,
      'bedSize' => $bedSize,
    ])
  </div>
@endsection
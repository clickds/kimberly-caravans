@extends('layouts.admin')

@section('title', 'Edit Bed Size')

@section('page-title', 'Edit Bed Size')

@section('page')
  <div class="w1/2">
    @include('admin.motorhome.bed-sizes._form', [
      'url' => route('admin.motorhomes.bed-sizes.update', [
        'bed_size' => $bedSize,
        'motorhome' => $motorhome,
      ]),
      'bedDescriptions' => $bedDescriptions,
      'bedSize' => $bedSize,
    ])
  </div>
@endsection
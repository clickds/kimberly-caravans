@extends('layouts.admin')

@section('title', 'Edit Bed Size')

@section('page-title', 'Edit Bed Size')

@section('page')
  <div class="w1/2">
    @include('admin.caravan.bed-sizes._form', [
      'url' => route('admin.caravans.bed-sizes.update', [
        'bed_size' => $bedSize,
        'caravan' => $caravan,
      ]),
      'bedDescriptions' => $bedDescriptions,
      'bedSize' => $bedSize,
    ])
  </div>
@endsection
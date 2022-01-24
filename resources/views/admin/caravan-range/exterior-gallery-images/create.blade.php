@extends('layouts.admin')

@section('title', 'Create Image')

@section('page-title', 'Create Image')

@section('page')
  <div>
    @include('admin.gallery-images._form', [
      'url' => route('admin.caravan-ranges.exterior-gallery-images.store', $caravanRange),
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Image')

@section('page-title', 'Create Image')

@section('page')
  <div>
    @include('admin.gallery-images._form', [
      'url' => route('admin.caravan-ranges.interior-gallery-images.store', $caravanRange),
    ])
  </div>
@endsection
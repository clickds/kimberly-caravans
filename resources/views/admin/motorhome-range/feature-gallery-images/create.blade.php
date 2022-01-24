@extends('layouts.admin')

@section('title', 'Create Image')

@section('page-title', 'Create Image')

@section('page')
  <div>
    @include('admin.gallery-images._form', [
      'url' => route('admin.motorhome-ranges.feature-gallery-images.store', $motorhomeRange),
    ])
  </div>
@endsection
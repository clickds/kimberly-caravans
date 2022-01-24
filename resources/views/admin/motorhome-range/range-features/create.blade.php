@extends('layouts.admin')

@section('title', 'Create Feature')

@section('page-title', 'Create Feature')

@section('page')
  <div>
    @include('admin.range-features._form', [
      'url' => route('admin.motorhome-ranges.range-features.store', $motorhomeRange),
      'rangeFeature' => $rangeFeature,
      'sites' => $sites,
    ])
  </div>
@endsection
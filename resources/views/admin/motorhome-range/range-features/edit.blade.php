@extends('layouts.admin')

@section('title', 'Edit Feature')

@section('page-title', 'Edit Feature')

@section('page')
  <div>
    @include('admin.range-features._form', [
      'url' => route('admin.motorhome-ranges.range-features.update', [
        'motorhomeRange' => $motorhomeRange,
        'range_feature' => $rangeFeature,
      ]),
      'rangeFeature' => $rangeFeature,
      'sites' => $sites,
    ])
  </div>
@endsection
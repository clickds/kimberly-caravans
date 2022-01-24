@extends('layouts.admin')

@section('title', 'Create Specification Small Print')

@section('page-title', 'Create Specification Small Print')

@section('page')
  <div>
    @include('admin.range-specification-small-prints._form', [
      'url' => route('admin.motorhome-ranges.range-specification-small-prints.store', $motorhomeRange),
      'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
      'sites' => $sites,
    ])
  </div>
@endsection
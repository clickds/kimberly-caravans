@extends('layouts.admin')

@section('title', 'Create Specification Small Print')

@section('page-title', 'Create Specification Small Print')

@section('page')
  <div>
    @include('admin.range-specification-small-prints._form', [
      'url' => route('admin.caravan-ranges.range-specification-small-prints.store', $caravanRange),
      'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
      'sites' => $sites,
    ])
  </div>
@endsection
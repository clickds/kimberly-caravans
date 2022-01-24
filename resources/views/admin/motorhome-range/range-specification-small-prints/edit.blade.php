@extends('layouts.admin')

@section('title', 'Edit Specification Small Print')

@section('page-title', 'Edit Specification Small Print')

@section('page')
  <div>
    @include('admin.range-specification-small-prints._form', [
      'url' => route('admin.motorhome-ranges.range-specification-small-prints.update', [
        'motorhomeRange' => $motorhomeRange,
        'range_specification_small_print' => $rangeSpecificationSmallPrint,
      ]),
      'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
      'sites' => $sites,
    ])
  </div>
@endsection
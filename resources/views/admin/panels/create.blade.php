@extends('layouts.admin')

@section('title', 'Create Panel')

@section('page-title', 'Create Panel')

@section('page')
  <div>
    @include('admin.panels._form', [
      'areas' => $areas,
      'panel' => $panel,
      'types' => $types,
      'siteId' => $siteId,
      'url' => route('admin.areas.panels.store', $area),
      'headingTypes' => $headingTypes,
      'overlayPositions' => $overlayPositions,
      'textAlignments' => $textAlignments,
      'verticalPositions' => $verticalPositions,
      'vehicleTypes' => $vehicleTypes,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Edit Panel')

@section('page-title', 'Edit Panel')

@section('page')
  <div>
    @include('admin.panels._form', [
      'areas' => $areas,
      'panel' => $panel,
      'types' => $types,
      'siteId' => $siteId,
      'url' => route('admin.areas.panels.update', ['area' => $area, 'panel' => $panel]),
      'headingTypes' => $headingTypes,
      'overlayPositions' => $overlayPositions,
      'textAlignments' => $textAlignments,
      'verticalPositions' => $verticalPositions,
      'vehicleTypes' => $vehicleTypes,
    ])
  </div>
@endsection
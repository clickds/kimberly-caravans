@extends('layouts.admin')

@section('title', 'Edit Area')

@section('page-title', 'Edit Area')

@section('page')
  <div class="w-full">
    @include('admin.areas._form', [
      'area' => $area,
      'url' => route('admin.pages.areas.update', ['page' => $page->id, 'area' => $area->id]),
      'backgroundColours' => $backgroundColours,
      'columns' => $columns,
      'widths' => $widths,
      'holders' => $holders,
      'headingTypes' => $headingTypes,
      'textAlignments' => $textAlignments,
    ])
  </div>
@endsection
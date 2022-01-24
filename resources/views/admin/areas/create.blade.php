@extends('layouts.admin')

@section('title', 'Create Area')

@section('page-title', 'Create Area')

@section('page')
  <div class="w-full">
    @include('admin.areas._form', [
      'area' => $area,
      'url' => route('admin.pages.areas.store', ['page' => $page->id]),
      'backgroundColours' => $backgroundColours,
      'columns' => $columns,
      'widths' => $widths,
      'holders' => $holders,
      'headingTypes' => $headingTypes,
      'textAlignments' => $textAlignments,
    ])
  </div>
@endsection
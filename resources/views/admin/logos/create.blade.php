@extends('layouts.admin')

@section('title', 'Create Logo')

@section('page-title', 'Create Logo')

@section('page')
  <div>
    @include('admin.logos._form', [
      'url' => route('admin.logos.store'),
      'logo' => $logo,
      'displayLocations' => $displayLocations,
    ])
  </div>
@endsection
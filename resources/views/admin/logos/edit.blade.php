@extends('layouts.admin')

@section('title', 'Edit Logo')

@section('page-title', 'Edit Logo')

@section('page')
  <div>
    @include('admin.logos._form', [
      'url' => route('admin.logos.update', ['logo' => $logo]),
      'logo' => $logo,
      'displayLocations' => $displayLocations,
    ])
  </div>
@endsection
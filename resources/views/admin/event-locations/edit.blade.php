@extends('layouts.admin')

@section('title', 'Edit Event Location')

@section('page-title', 'Edit Event Location')

@section('page')
  <div class="w1/2">
    @include('admin.event-locations._form', [
      'url' => route('admin.event-locations.update', $eventLocation),
      'eventLocation' => $eventLocation,
    ])
  </div>
@endsection
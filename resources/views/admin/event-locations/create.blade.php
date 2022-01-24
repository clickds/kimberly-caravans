@extends('layouts.admin')

@section('title', 'Create Event Location')

@section('page-title', 'Create Event Location')

@section('page')
  <div class="w1/2">
    @include('admin.event-locations._form', [
      'url' => route('admin.event-locations.store'),
      'eventLocation' => $eventLocation,
    ])
  </div>
@endsection
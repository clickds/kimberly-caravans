@extends('layouts.admin')

@section('title', 'Create Event')

@section('page-title', 'Create Event')

@section('page')
  <div class="w1/2">
    @include('admin.events._form', [
      'url' => route('admin.events.store'),
      'event' => $event,
      'dealers' => $dealers,
      'locations' => $locations,
    ])
  </div>
@endsection
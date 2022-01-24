@extends('layouts.admin')

@section('title', 'Edit Event')

@section('page-title', 'Edit Event')

@section('page')
  <div class="w1/2">
    @include('admin.events._form', [
      'url' => route('admin.events.update', $event),
      'event' => $event,
      'dealers' => $dealers,
      'locations' => $locations,
    ])
  </div>
@endsection
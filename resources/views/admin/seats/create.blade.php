@extends('layouts.admin')

@section('title', 'Create Seat')

@section('page-title', 'Create Seat')

@section('page')
  <div class="w-full">
    @include('admin.seats._form', [
      'url' => route('admin.seats.store'),
      'seat' => $seat,
    ])
  </div>
@endsection
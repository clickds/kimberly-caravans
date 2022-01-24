@extends('layouts.admin')

@section('title', 'Edit Seat')

@section('page-title', 'Edit Seat')

@section('page')
  <div class="w-full">
    @include('admin.seats._form', [
      'url' => route('admin.seats.update', $seat),
      'seat' => $seat,
    ])
  </div>
@endsection
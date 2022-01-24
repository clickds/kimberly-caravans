@extends('layouts.admin')

@section('title', 'Edit Berth')

@section('page-title', 'Edit Berth')

@section('page')
  <div class="w-full">
    @include('admin.berths._form', [
      'url' => route('admin.berths.update', $berth),
      'berth' => $berth,
    ])
  </div>
@endsection
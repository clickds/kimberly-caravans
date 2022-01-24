@extends('layouts.admin')

@section('title', 'Create Berth')

@section('page-title', 'Create Berth')

@section('page')
  <div class="w-full">
    @include('admin.berths._form', [
      'url' => route('admin.berths.store'),
      'berth' => $berth,
    ])
  </div>
@endsection
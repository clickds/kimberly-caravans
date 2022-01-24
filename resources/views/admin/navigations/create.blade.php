@extends('layouts.admin')

@section('title', 'Create Navigation')

@section('page-title', 'Create Navigation')

@section('page')
  <div class="w1/2">
    @include('admin.navigations._form', [
      'url' => route('admin.navigations.store'),
      'sites' => $sites,
    ])
  </div>
@endsection
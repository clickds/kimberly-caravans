@extends('layouts.admin')

@section('title', 'Create Navigation Item')

@section('page-title', 'Create Navigation Item')

@section('page')
  <div class="w1/2">
    @include('admin.navigation.navigation-items._form', [
      'url' => route('admin.navigations.navigation-items.store', ['navigation' => $navigation]),
      'navigation' => $navigation,
      'navigationItem' => $navigationItem,
      'navigationItems' => $navigationItems,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Edit Navigation Item')

@section('page-title', 'Edit Navigation Item')

@section('page')
  <div class="w1/2">
    @include('admin.navigation.navigation-items._form', [
      'url' => route(
        'admin.navigations.navigation-items.update',
        ['navigation' => $navigation, 'navigation_item' => $navigationItem]
      ),
      'navigation' => $navigation,
      'navigationItem' => $navigationItem,
      'navigationItems' => $navigationItems,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Stock Search Link')

@section('page-title', 'Create Stock Search Link')

@section('page')
  @include('admin.stock-search-links._form', [
    'url' => route('admin.stock-search-links.store'),
    'stockSearchLink' => $stockSearchLink,
    'sites' => $sites,
    'types' => $types,
  ])
@endsection
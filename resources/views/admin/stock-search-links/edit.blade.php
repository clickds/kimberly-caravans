@extends('layouts.admin')

@section('title', 'Edit Stock Search Link')

@section('page-title', 'Edit Stock Search Link')

@section('page')
  @include('admin.stock-search-links._form', [
    'url' => route('admin.stock-search-links.update', $stockSearchLink),
    'stockSearchLink' => $stockSearchLink,
    'sites' => $sites,
    'types' => $types,
  ])
@endsection
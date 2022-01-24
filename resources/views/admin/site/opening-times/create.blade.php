@extends('layouts.admin')

@section('title', 'Create Opening Time')

@section('page-title', 'Create Opening Time')

@section('page')
  @include('admin.site.opening-times._form', [
    'days' => $days,
    'openingTime' => $openingTime,
    'url' => route('admin.sites.opening-times.store', $site),
  ])
@endsection
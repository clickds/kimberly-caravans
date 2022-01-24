@extends('layouts.admin')

@section('title', 'Edit Opening Time')

@section('page-title', 'Edit Opening Time')

@section('page')
@include('admin.site.opening-times._form', [
  'days' => $days,
  'openingTime' => $openingTime,
  'url' => route('admin.sites.opening-times.update', [
    'site' => $site,
    'opening_time' => $openingTime,
  ]),
])
@endsection
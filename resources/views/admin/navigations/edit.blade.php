@extends('layouts.admin')

@section('title', 'Edit Navigation')

@section('page-title', 'Edit Navigation')

@section('page')
  <div class="w1/2">
    @include('admin.navigations._form', [
      'url' => route('admin.navigations.update', $navigation),
      'navigation' => $navigation,
    ])
  </div>
@endsection
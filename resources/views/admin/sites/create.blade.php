@extends('layouts.admin')

@section('title', 'Create Site')

@section('page-title', 'Create Site')

@section('page')
  <div class="w1/2">
    @include('admin.sites._form', [
      'url' => route('admin.sites.store'),
      'flags' => $flags,
      'site' => $site,
    ])
  </div>
@endsection
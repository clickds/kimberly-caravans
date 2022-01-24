@extends('layouts.admin')

@section('title', 'Edit Site')

@section('page-title', 'Edit Site')

@section('page')
  <div class="w1/2">
    @include('admin.sites._form', [
      'url' => route('admin.sites.update', $site),
      'flags' => $flags,
      'site' => $site,
    ])
  </div>
@endsection
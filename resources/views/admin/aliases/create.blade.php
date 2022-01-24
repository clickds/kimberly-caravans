@extends('layouts.admin')

@section('title', 'Create Alias')

@section('page-title', 'Create Alias')

@section('page')
  <div>
    @include('admin.aliases._form', [
      'url' => route('admin.aliases.store'),
      'alias' => $alias,
      'sites' => $sites,
    ])
  </div>
@endsection
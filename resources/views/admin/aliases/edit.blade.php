@extends('layouts.admin')

@section('title', 'Edit Alias')

@section('page-title', 'Edit Alias')

@section('page')
  <div>
    @include('admin.aliases._form', [
      'url' => route('admin.aliases.update', $alias),
      'alias' => $alias,
      'sites' => $sites,
    ])
  </div>
@endsection
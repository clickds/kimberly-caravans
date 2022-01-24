@extends('layouts.admin')

@section('title', 'Create Layout')

@section('page-title', 'Create Layout')

@section('page')
  <div class="w-1/2">
    @include(
      'admin.layouts._form',
      [
        'url' => route('admin.layouts.create'),
        'layout' => $layout
      ]
    )
  </div>
@endsection
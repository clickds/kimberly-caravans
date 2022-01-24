@extends('layouts.admin')

@section('title', 'Edit Layout')

@section('page-title', 'Edit Layout')

@section('page')
  <div class="w-1/2">
    @include(
      'admin.layouts._form',
      [
        'url' => route('admin.layouts.update', ['layout' => $layout]),
        'layout' => $layout
      ]
    )
  </div>
@endsection
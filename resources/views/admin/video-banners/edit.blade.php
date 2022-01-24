@extends('layouts.admin')

@section('title', 'Edit Video Banner')

@section('page-title', 'Edit Video Banner')

@section('page')
  <div class="w-full">
    @include('admin.video-banners._form', [
      'videoBanner' => $videoBanner,
      'url' => route('admin.video-banners.update', $videoBanner)
    ])
  </div>
@endsection
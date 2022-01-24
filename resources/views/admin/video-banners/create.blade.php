@extends('layouts.admin')

@section('title', 'Create Video Banner')
@section('page-title', 'Create Video Banner')

@section('page')
  <div class="w-full">
    @include('admin.video-banners._form', [
      'videoBanner' => $videoBanner,
      'url' => route('admin.video-banners.store')
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Video Category')
@section('page-title', 'Create Video Category')

@section('page')
  <div class="w-full">
    @include('admin.video-categories._form', [
      'video_category' => $video_category,
      'url' => route('admin.video-categories.store')
    ])
  </div>
@endsection
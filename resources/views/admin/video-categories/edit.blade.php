@extends('layouts.admin')

@section('title', 'Edit Video Category')

@section('page-title', 'Edit Video Category')

@section('page')
  <div class="w-full">
    @include('admin.video-categories._form', [
      'video_category' => $video_category,
      'url' => route('admin.video-categories.update', $video_category)
    ])
  </div>
@endsection
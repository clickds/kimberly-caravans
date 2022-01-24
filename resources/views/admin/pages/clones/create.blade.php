@extends('layouts.admin')

@section('title', 'Clone Page')

@section('page-title', 'Clone Page')

@section('page')
  <div class="w-full">
    @include('admin.pages.clones._form', [
      'url' => route('admin.pages.clones.store', ['page' => $page]),
      'imageBanners' => $imageBanners,
      'page' => $page,
      'pages' => $pages,
      'sites' => $sites,
      'templates' => $templates,
      'varieties' => $varieties,
      'videoBanners' => $videoBanners,
    ])
  </div>
@endsection
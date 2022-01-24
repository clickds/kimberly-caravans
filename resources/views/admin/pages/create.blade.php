@extends('layouts.admin')

@section('title', 'Create Page')

@section('page-title', 'Create Page')

@section('page')
  <div class="w-full">
    @include('admin.pages._form', [
      'url' => route('admin.pages.store'),
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
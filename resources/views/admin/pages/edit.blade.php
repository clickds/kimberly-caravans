@extends('layouts.admin')

@section('title', 'Edit Page')

@section('page-title', 'Edit Page')

@section('page')
<div class="w-full">
  @include('admin.pages._form', [
    'url' => route('admin.pages.update', $page),
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
@extends('layouts.admin')

@section('title', 'Edit Useful Link')

@section('page-title', 'Edit Useful Link')

@section('page')
  <div class="h-full flex items-center justify-center">
    <div class="w-full">
      @include('admin.useful-links._form', [
        'url' => route('admin.useful-links.update', $usefulLink),
        'usefulLink' => $usefulLink,
        'usefulLinkCategories' => $usefulLinkCategories,
      ])
    </div>
  </div>
@endsection
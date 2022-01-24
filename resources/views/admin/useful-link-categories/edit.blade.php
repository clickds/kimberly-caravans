@extends('layouts.admin')

@section('title', 'Edit Useful Link Category')

@section('page-title', 'Edit Useful Link Category')

@section('page')
  <div class="h-full flex items-center justify-center">
    <div class="w-full">
      @include('admin.useful-link-categories._form', [
        'url' => route('admin.useful-link-categories.update', $usefulLinkCategory),
        'usefulLinkCategory' => $usefulLinkCategory,
      ])
    </div>
  </div>
@endsection
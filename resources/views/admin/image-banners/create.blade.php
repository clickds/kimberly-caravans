@extends('layouts.admin')

@section('title', 'Create Image Banner')

@section('page-title', 'Create Image Banner')

@section('page')
  <div class="w-full">
    @include('admin.image-banners._form', [
      'url' => route('admin.image-banners.store'),
      'imageBanner' => $imageBanner,
      'textColours' => $textColours,
      'backgroundColours' => $backgroundColours,
      'icons' => $icons,
      'textAlignments' => $textAlignments,
    ])
  </div>
@endsection
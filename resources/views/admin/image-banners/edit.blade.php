@extends('layouts.admin')

@section('title', 'Edit Image Banner')

@section('page-title', 'Edit Image Banner')

@section('page')
  <div class="w-full">
    @include('admin.image-banners._form', [
      'url' => route('admin.image-banners.update', $imageBanner),
      'imageBanner' => $imageBanner,
      'textColours' => $textColours,
      'backgroundColours' => $backgroundColours,
      'icons' => $icons,
      'textAlignments' => $textAlignments,
    ])
  </div>
@endsection
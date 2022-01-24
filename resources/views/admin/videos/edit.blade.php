@extends('layouts.admin')

@section('title', 'Edit Video')

@section('page-title', 'Edit Video')

@section('page')
  <div class="w1/2">
    @include('admin.videos._form', [
      'url' => route('admin.videos.update', $video),
      'video' => $video,
      'sites' => $sites,
      'caravanRanges' => $caravanRanges,
      'motorhomeRanges' => $motorhomeRanges,
      'dealers' => $dealers,
    ])
  </div>
@endsection
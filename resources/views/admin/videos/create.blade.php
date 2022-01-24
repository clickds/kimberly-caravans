@extends('layouts.admin')

@section('title', 'Create Video')

@section('page-title', 'Create Video')

@section('page')
  <div class="w1/2">
    @include('admin.videos._form', [
      'url' => route('admin.videos.store'),
      'video' => $video,
      'sites' => $sites,
      'caravanRanges' => $caravanRanges,
      'motorhomeRanges' => $motorhomeRanges,
      'dealers' => $dealers,
    ])
  </div>
@endsection
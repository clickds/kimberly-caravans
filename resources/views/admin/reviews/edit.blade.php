@extends('layouts.admin')

@section('title', 'Edit Review')

@section('page-title', 'Edit Review')

@section('page')
  <div class="w1/2">
    @include('admin.reviews._form', [
      'url' => route('admin.reviews.update', $review),
      'caravanRanges' => $caravanRanges,
      'caravanRangeIds' => $caravanRangeIds,
      'motorhomeRanges' => $motorhomeRanges,
      'motorhomeRangeIds' => $motorhomeRangeIds,
      'dealers' => $dealers,
      'review' => $review,
      'sites' => $sites,
    ])
  </div>
@endsection
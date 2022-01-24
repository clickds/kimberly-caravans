@extends('layouts.admin')

@section('title', 'Create Review')

@section('page-title', 'Create Review')

@section('page')
  <div class="w1/2">
    @include('admin.reviews._form', [
      'url' => route('admin.reviews.store'),
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
@extends('layouts.admin')

@section('title', 'Create Caravan')

@section('page-title', 'Create Caravan')

@section('page')
  <div class="w1/2">
    @include('admin.caravan-range.caravans._form', [
      'url' => route('admin.caravan-ranges.caravans.store', $caravanRange),
      'axles' => $axles,
      'berths' => $berths,
      'caravan' => $caravan,
      'layouts' => $layouts,
      'rangeGalleryImages' => $rangeGalleryImages,
      'stockItemImageIds' => $stockItemImageIds,
      'sites' => $sites,
      'videos' => $videos,
    ])
  </div>
@endsection
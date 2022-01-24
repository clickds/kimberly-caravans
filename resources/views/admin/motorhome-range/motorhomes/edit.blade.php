@extends('layouts.admin')

@section('title', 'Edit Motorhome')

@section('page-title', 'Edit Motorhome')

@section('page')
  <div class="w1/2">
    @include('admin.motorhome-range.motorhomes._form', [
      'url' => route('admin.motorhome-ranges.motorhomes.update', [
        'motorhomeRange' => $motorhomeRange,
        'motorhome' => $motorhome,
      ]),
      'fuels' => $fuels,
      'conversions' => $conversions,
      'transmissions' => $transmissions,
      'motorhome' => $motorhome,
      'layouts' => $layouts,
      'rangeGalleryImages' => $rangeGalleryImages,
      'stockItemImageIds' => $stockItemImageIds,
      'sites' => $sites,
      'videos' => $videos,
      'berths' => $berths,
      'seats' => $seats,
    ])
  </div>
@endsection
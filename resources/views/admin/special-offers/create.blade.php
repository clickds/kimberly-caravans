@extends('layouts.admin')

@section('title', 'Create Special Offer')

@section('page-title', 'Create Special Offer')

@section('page')
  @include('admin.special-offers._form', [
    'url' => route('admin.special-offers.store'),
    'currentSiteIds' => $currentSiteIds,
    'currentCaravanIds' => $currentCaravanIds,
    'manufacturers' => $manufacturers,
    'offerTypes' => $offerTypes,
    'specialOffer' => $specialOffer,
    'sites' => $sites,
    'stockBarColours' => $stockBarColours,
    'types' => $types,
    "currentCaravanFeedStockItems" => $currentCaravanFeedStockItems,
    "currentMotorhomeFeedStockItems" => $currentMotorhomeFeedStockItems,
  ])
@endsection
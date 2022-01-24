@extends('layouts.admin')

@section('title', 'Create Article')

@section('page-title', 'Create Article')

@section('page')
  <div class="w1/2">
    @include('admin.articles._form', [
      'url' => route('admin.articles.store'),
      'article' => $article,
      'articleCategories' => $articleCategories,
      'articleCategoryIds' => $articleCategoryIds,
      'caravanRanges' => $caravanRanges,
      'caravanRangeIds' => $caravanRangeIds,
      'dealers' => $dealers,
      'motorhomeRanges' => $motorhomeRanges,
      'motorhomeRangeIds' => $motorhomeRangeIds,
      'types' => $types,
      'styles' => $styles,
    ])
  </div>
@endsection
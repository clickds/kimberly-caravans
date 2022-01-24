@extends('layouts.admin')

@section('title', 'Edit Article')

@section('page-title', 'Edit Article')

@section('page')
  <div class="w1/2">
    @include('admin.articles._form', [
      'url' => route('admin.articles.update', $article),
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
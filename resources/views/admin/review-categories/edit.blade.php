@extends('layouts.admin')

@section('title', 'Edit Review Category')

@section('page-title', 'Edit Review Category')

@section('page')
  <div class="w1/2">
    @include('admin.review-categories._form', [
      'url' => route('admin.review-categories.update', $reviewCategory),
      'reviewCategory' => $reviewCategory,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Review Category')

@section('page-title', 'Create Review Category')

@section('page')
  <div class="w1/2">
    @include('admin.review-categories._form', [
      'url' => route('admin.review-categories.store'),
      'reviewCategory' => $reviewCategory,
    ])
  </div>
@endsection
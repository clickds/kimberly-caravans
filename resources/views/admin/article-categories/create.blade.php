@extends('layouts.admin')

@section('title', 'Create Article Category')

@section('page-title', 'Create Article Category')

@section('page')
    <div class="h-full flex items-center justify-center">
        <div class="w-full">
            @include('admin.article-categories._form', [
              'url' => route('admin.article-categories.store'),
              'articleCategory' => $articleCategory,
            ])
        </div>
    </div>
@endsection
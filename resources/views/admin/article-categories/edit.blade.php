@extends('layouts.admin')

@section('title', 'Edit Article Category')

@section('page-title', 'Article Categories / ' . $articleCategory->name . ' / Edit')

@section('page')
    <div class="h-full flex items-center justify-center">
        <div class="w-full">
            @include('admin.article-categories._form', [
              'url' => route('admin.article-categories.update', $articleCategory),
              'articleCategory' => $articleCategory,
            ])
        </div>
    </div>
@endsection
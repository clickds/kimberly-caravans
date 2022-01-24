@extends('layouts.admin')

@section('title', 'Create Useful Link Category')

@section('page-title', 'Create Useful Link Category')

@section('page')
    <div class="h-full flex items-center justify-center">
        <div class="w-full">
            @include('admin.useful-link-categories._form', [
              'url' => route('admin.useful-link-categories.store'),
              'usefulLinkCategory' => $usefulLinkCategory,
            ])
        </div>
    </div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Useful Link')

@section('page-title', 'Create Useful Link')

@section('page')
    <div class="h-full flex items-center justify-center">
        <div class="w-full">
            @include('admin.useful-links._form', [
              'url' => route('admin.useful-links.store'),
              'usefulLink' => $usefulLink,
              'usefulLinkCategories' => $usefulLinkCategories,
            ])
        </div>
    </div>
@endsection
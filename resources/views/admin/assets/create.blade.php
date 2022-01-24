@extends('layouts.admin')

@section('title', 'Create Assets')

@section('page-title', 'Create Asset')

@section('page')
    <div class="h-full flex items-center justify-center">
        <div class="w-full">
            @include('admin.assets._form', [
              'url' => route('admin.assets.store'),
              'asset' => $asset,
            ])
        </div>
    </div>
@endsection
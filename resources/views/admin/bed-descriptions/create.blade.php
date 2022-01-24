@extends('layouts.admin')

@section('title', 'Create Bed Description')

@section('page-title', 'Create Bed Description')

@section('page')
    <div class="h-full flex items-center justify-center">
        <div class="w-full">
            @include('admin.bed-descriptions._form', [
              'url' => route('admin.bed-descriptions.store'),
              'bedDescription' => $bedDescription,
            ])
        </div>
    </div>
@endsection
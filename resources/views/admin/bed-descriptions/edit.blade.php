@extends('layouts.admin')

@section('title', 'Edit Bed Description')

@section('page-title', 'Edit Bed Description')

@section('page')
    <div class="h-full flex items-center justify-center">
        <div class="w-full">
            @include('admin.bed-descriptions._form', [
              'url' => route('admin.bed-descriptions.update', $bedDescription),
              'bedDescription' => $bedDescription,
            ])
        </div>
    </div>
@endsection
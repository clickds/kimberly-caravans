@extends('layouts.admin')

@section('title', 'Upload Multiple Images')

@section('page-title', 'Upload Multiple Images')

@section('page-actions')
  @include('admin._partials.button', [
    'url' => url()->previous(),
    'text' => 'Back',
  ])
@endsection

@section('page')
  <div>
    <uppy-uploader :csrf-token='@json(csrf_token())' :url='@json($storeUrl)'></uppy-uploader>
  </div>
@endsection
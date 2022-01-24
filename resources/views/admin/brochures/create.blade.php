@extends('layouts.admin')

@section('title', 'Create Brochure')

@section('page-title', 'Create Brochure')

@section('page')
  <div class="w1/2">
    @include('admin.brochures._form', [
      'url' => route('admin.brochures.store'),
      'brochure' => $brochure,
    ])
  </div>
@endsection
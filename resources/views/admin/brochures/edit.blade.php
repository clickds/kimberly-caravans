@extends('layouts.admin')

@section('title', 'Edit Brochure')

@section('page-title', 'Edit Brochure')

@section('page')
  <div class="w1/2">
    @include('admin.brochures._form', [
      'url' => route('admin.brochures.update', $brochure),
      'brochure' => $brochure,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Testimonial')

@section('page-title', 'Create Testimonial')

@section('page')
  <div class="w1/2">
    @include('admin.testimonials._form', [
      'url' => route('admin.testimonials.store'),
      'sites' => $sites,
      'testimonial' => $testimonial,
    ])
  </div>
@endsection
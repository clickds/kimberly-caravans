@extends('layouts.admin')

@section('title', 'Edit Testimonial')

@section('page-title', 'Edit Testimonial')

@section('page')
  <div class="w1/2">
    @include('admin.testimonials._form', [
      'url' => route('admin.testimonials.update', $testimonial),
      'sites' => $sites,
      'testimonial' => $testimonial,
    ])
  </div>
@endsection
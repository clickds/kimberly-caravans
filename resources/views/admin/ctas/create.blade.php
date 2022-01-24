@extends('layouts.admin')

@section('title', 'Create Cta')

@section('page-title', 'Create Cta')

@section('page')
  <div class="w1/2">
    @include('admin.ctas._form', [
      'url' => route('admin.ctas.store'),
      'cta' => $cta,
      'sites' => $sites,
      'types' => $types,
    ])
  </div>
@endsection
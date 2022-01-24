@extends('layouts.admin')

@section('title', 'Edit Cta')

@section('page-title', 'Edit Cta')

@section('page')
  <div class="w1/2">
    @include('admin.ctas._form', [
      'url' => route('admin.ctas.update', $cta),
      'cta' => $cta,
      'sites' => $sites,
      'types' => $types,
    ])
  </div>
@endsection
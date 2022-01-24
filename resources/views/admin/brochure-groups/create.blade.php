@extends('layouts.admin')

@section('title', 'Create Brochure Group')

@section('page-title', 'Create Brochure Group')

@section('page')
  <div class="w1/2">
    @include('admin.brochure-groups._form', [
      'url' => route('admin.brochure-groups.store'),
      'brochure_group' => $brochure_group,
    ])
  </div>
@endsection
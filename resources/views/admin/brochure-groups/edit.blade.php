@extends('layouts.admin')

@section('title', 'Edit Brochure Group')

@section('page-title', 'Edit Brochure Group')

@section('page')
  <div class="w1/2">

    @include('admin.brochure-groups._form', [
      'url' => route('admin.brochure-groups.update', $brochure_group),
      'brochure_group' => $brochure_group,
    ])
  </div>
@endsection
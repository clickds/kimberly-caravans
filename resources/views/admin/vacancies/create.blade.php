@extends('layouts.admin')

@section('title', 'Create Vacancy')

@section('page-title', 'Create Vacancy')

@section('page')
  <div class="w1/2">
    @include('admin.vacancies._form', [
      'url' => route('admin.vacancies.store'),
      'vacancy' => $vacancy,
      'dealers' => $dealers,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Edit Vacancy')

@section('page-title', 'Edit Vacancy')

@section('page')
  <div class="w1/2">
    @include('admin.vacancies._form', [
      'url' => route('admin.vacancies.update', $vacancy),
      'vacancy' => $vacancy,
      'dealers' => $dealers,
    ])
  </div>
@endsection
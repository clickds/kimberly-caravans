@extends('layouts.admin')

@section('title', 'Create Employee')

@section('page-title', 'Create Employee')

@section('page')
  <div>
    @include('admin.dealer.employees._form', [
      'url' => route('admin.dealers.employees.store', $dealer),
      'employee' => $employee,
    ])
  </div>
@endsection
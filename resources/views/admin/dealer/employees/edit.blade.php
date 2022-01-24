@extends('layouts.admin')

@section('title', 'Edit Employee')

@section('page-title', 'Edit Employee')

@section('page')
  <div>
    @include('admin.dealer.employees._form', [
      'url' => route('admin.dealers.employees.update', [
        'dealer' => $dealer,
        'employee' => $employee,
      ]),
    ])
  </div>
@endsection
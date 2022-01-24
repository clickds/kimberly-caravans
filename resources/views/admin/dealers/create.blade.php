@extends('layouts.admin')

@section('title', 'Create Dealer')

@section('page-title', 'Create Dealer')

@section('page')
  <div>
    @include('admin.dealers._form', [
      'url' => route('admin.dealers.store'),
      'dealer' => $dealer,
      'location' => $location,
    ])
  </div>
@endsection
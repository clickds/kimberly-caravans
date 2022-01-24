@extends('layouts.admin')

@section('title', 'Edit Dealer')

@section('page-title', 'Edit Dealer')

@section('page')
  <div>
    @include('admin.dealers._form', [
      'url' => route('admin.dealers.update', $dealer),
      'dealer' => $dealer,
      'location' => $location,
    ])
  </div>
@endsection
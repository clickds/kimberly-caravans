@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create User')

@section('page')
  <div class="w-full">
    @include('admin.users._form', [
      'user' => $user,
      'url' => route('admin.users.store')
    ])
  </div>
@endsection
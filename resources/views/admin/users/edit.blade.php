@extends('layouts.admin')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('page')
  <div class="w-full">
    @include('admin.users._form', [
      'user' => $user,
      'url' => route('admin.users.update', $user)
    ])
  </div>
@endsection
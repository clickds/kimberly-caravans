@extends('layouts.admin')

@section('title', 'Create Button')

@section('page-title', 'Create Button')

@section('page')
  <div>
    @include('admin.buttonable.buttons._form', [
      'url' => route('admin.' . $pluralButtonableRouteName . '.buttons.store', $buttonable),
      'button' => $button,
      'colours' => $colours,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Edit Button')

@section('page-title', 'Edit Button')

@section('page')
  <div>
    @include('admin.buttonable.buttons._form', [
      'url' => route('admin.' . $pluralButtonableRouteName . '.buttons.update', [
        'buttonable' => $buttonable,
        'button' => $button,
      ]),
      'button' => $button,
      'colours' => $colours,
    ])
  </div>
@endsection
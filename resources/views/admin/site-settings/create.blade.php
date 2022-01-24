@extends('layouts.admin')

@section('title', 'Create Site Setting')

@section('page-title', 'Create Site Setting')

@section('page')
  <div>
    @include('admin.site-settings._form', [
      'url' => route('admin.site-settings.store'),
      'siteSetting' => $siteSetting,
      'siteSettingNames' => $siteSettingNames,
    ])
  </div>
@endsection
@extends('layouts.admin')

@section('title', 'Edit Site Setting')

@section('page-title', 'Edit Site Setting')

@section('page')
  <div>
    @include('admin.site-settings._form', [
      'url' => route('admin.site-settings.update', ['site_setting' => $siteSetting]),
      'siteSetting' => $siteSetting,
      'siteSettingNames' => $siteSettingNames,
    ])
  </div>
@endsection
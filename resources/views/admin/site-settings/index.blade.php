@extends('layouts.admin')

@section('title', 'Site Settings')

@section('page-title', 'Site Settings')

@section('page-actions')
  @include('admin._partials.button', [
    'text' => 'New Site Setting',
    'url' => route('admin.site-settings.create')
  ])
@endsection

@section('page')
  @if($siteSettings->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Description</td>
        <td>Value</td>
        <td>Actions</td>
      </thead>
      @foreach($siteSettings as $siteSetting)
        <tr>
          <td>{{ $siteSetting->id }}</td>
          <td>{{ $siteSetting->name }}</td>
          <td>{{ $siteSetting->description }}</td>
          <td>{{ $siteSetting->value }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => route('admin.site-settings.edit', [
                'site_setting' => $siteSetting,
              ]),
              'destroy' => route('admin.site-settings.destroy', [
                'site_setting' => $siteSetting,
              ]),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
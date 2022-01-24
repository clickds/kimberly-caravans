@extends('layouts.admin')

@section('title', 'Sites')

@section('page-title', 'Sites')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.sites.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Site</a>
@endsection

@section('page')
  @if($sites->count() > 0)
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Country</td>
        <td>Phone Number</td>
        <td>Subdomain</td>
        <td>Is Default</td>
        <td>Opening Times</td>
        <td>Actions</td>
      </thead>
      @foreach($sites as $site)
        <tr>
          <td>{{ $site->id }}</td>
          <td>{{ $site->country }}</td>
          <td>{{ $site->phone_number }}</td>
          <td>{{ $site->subdomain }}</td>
          <td>{{ $site->is_default ? 'Y' : 'N' }}</td>
          <td>
            <a href="{{ route('admin.sites.opening-times.index', $site) }}">
              @choice('global.opening_times', $site->opening_times_count)
            </a>
          <td>
            <a href="{{ routeWithCurrentUrlAsRedirect('admin.sites.edit', $site) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
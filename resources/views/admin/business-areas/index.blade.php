@extends('layouts.admin')

@section('title', "Business Areas")

@section('page-title', "Business Areas")

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.business-areas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Business Area
  </a>
@endsection

@section('page')
  @if($businessAreas->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Email</td>
        <td>Actions</td>
      </thead>
      @foreach($businessAreas as $businessArea)
        <tr>
          <td>{{ $businessArea->id }}</td>
          <td>{{ $businessArea->name }}</td>
          <td>{{ $businessArea->email }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.business-areas.edit', $businessArea),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.business-areas.destroy', $businessArea),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
@extends('layouts.admin')

@section('title', 'Layouts')

@section('page-title', 'Layouts')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.layouts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Layout</a>
@endsection

@section('page')
  @include('admin.layouts._filter-form')

  @if($layouts->isNotEmpty())
    <table class="admin-table">
      <thead>
      <td>ID</td>
      <td>Code</td>
      <td>Name</td>
      <td>Actions</td>
      </thead>
      @foreach($layouts as $layout)
        <tr>
          <td>{{ $layout->id }}</td>
          <td>{{ $layout->code }}</td>
          <td>{{ $layout->name }}</td>
          <td>
            <a href="{{ routeWithCurrentUrlAsRedirect('admin.layouts.edit', ['layout' => $layout->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
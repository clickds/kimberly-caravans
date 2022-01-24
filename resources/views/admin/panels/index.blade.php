@extends('layouts.admin')

@section('title', 'Panels')

@section('page-title', 'Panels')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.areas.panels.create', $area) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Panel</a>
@endsection

@section('page')
  @if($panels->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Heading</td>
        <td>Type</td>
        <td>Actions</td>
      </thead>
      @foreach($panels as $panel)
        <tr>
          <td>{{ $panel->id }}</td>
          <td>{{ $panel->name }}</td>
          <td>{{ $panel->heading }}</td>
          <td>{{ $panel->humanisedType() }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.areas.panels.edit', ['area' => $area, 'panel' => $panel]),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.areas.panels.destroy', ['area' => $area, 'panel' => $panel])
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
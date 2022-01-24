@extends('layouts.admin')

@section('title', 'Areas')

@section('page-title', 'Areas')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.pages.areas.create', ['page' => $page->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Area</a>
@endsection

@section('page')
  @if($areas->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Holder</td>
        <td>Heading</td>
        <td>Layout</td>
        <td>Background Colour</td>
        <td>Published At</td>
        <td>Expired At</td>
        <td>Panels</td>
        <td>Actions</td>
      </thead>
      @foreach($areas as $area)
        <tr>
          <td>{{ $area->id }}</td>
          <td>{{ $area->name }}</td>
          <td>{{ !empty($area->holder) ? $area->holder : 'None' }}</td>
          <td>{{ !empty($area->heading) ? $area->heading : 'None' }}</td>
          <td>{{ !empty($area->layout) ? $area->layout : 'None' }}</td>
          <td>{{ !empty($area->background_colour) ? $area->background_colour : 'None' }}</td>
          <td>{{ $area->hasPublishedAtDate() ? $area->published_at : 'None' }}</td>
          <td>{{ $area->hasExpiredAtDate() ? $area->expired_at : 'None' }}</td>
          <td><a href="{{ route('admin.areas.panels.index', $area) }}" class="admin-link">{{ $area->panels->count() }}</a></td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.pages.areas.edit', ['page' => $page->id, 'area' => $area]),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.pages.areas.destroy', ['page' => $page->id, 'area' => $area])
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
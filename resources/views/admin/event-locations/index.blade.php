@extends('layouts.admin')

@section('title', 'Event Locations')

@section('page-title', 'Event Locations')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.event-locations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Event Location</a>
@endsection

@section('page')
  @include('admin.event-locations._filter-form')

  @if($eventLocations->isNotEmpty())
    <table class="admin-table">
      <thead>
      <td>ID</td>
      <td>Name</td>
      <td>Address</td>
      <td>Phone</td>
      <td>Fax</td>
      <td>Latitude</td>
      <td>Longitude</td>
      <td>Actions</td>
      </thead>
      @foreach($eventLocations as $eventLocation)
        <tr>
          <td>{{ $eventLocation->id }}</td>
          <td>{{ $eventLocation->name }}</td>
          <td>
            {{ $eventLocation->address_line_1 ? $eventLocation->address_line_1 . '<br>' : '' }}
            {{ $eventLocation->address_line_2 ? $eventLocation->address_line_2 . '<br>' : '' }}
            {{ $eventLocation->town ? $eventLocation->town . '<br>' : '' }}
            {{ $eventLocation->county ? $eventLocation->county . '<br>' : '' }}
            {{ $eventLocation->postcode }}
          </td>
          <td>{{ $eventLocation->phone }}</td>
          <td>{{ $eventLocation->fax }}</td>
          <td>{{ $eventLocation->latitude }}</td>
          <td>{{ $eventLocation->longitude }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.event-locations.edit', $eventLocation),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.event-locations.destroy', $eventLocation)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
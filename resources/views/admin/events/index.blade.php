@extends('layouts.admin')

@section('title', 'Events')

@section('page-title', 'Events')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.events.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Event</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Events Page'])
@endsection

@section('page')
  @include('admin.events._filter-form')

  @if($events->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Pages</td>
        <td>Actions</td>
      </thead>
      @foreach($events as $event)
        <tr>
          <td>{{ $event->id }}</td>
          <td>{{ $event->name }}</td>
          <td>{{ $event->start_date->format('d-m-Y') }}</td>
          <td>{{ $event->end_date->format('d-m-Y') }}</td>
          <td>
            @foreach ($event->pages as $page)
              <a href="{{ $page->link() }}" class="block" target="_blank">{{ $page->slug }}</a>
            @endforeach
          </td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.events.edit', $event),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.events.destroy', $event)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
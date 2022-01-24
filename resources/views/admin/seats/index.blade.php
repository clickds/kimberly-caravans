@extends('layouts.admin')

@section('title', 'Seats')

@section('page-title', 'Seats')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.seats.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Seat
  </a>
@endsection

@section('page')
  @if($seats->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Number</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($seats as $seat)
        <tr>
          <td>{{ $seat->id }}</td>
          <td>{{ $seat->number }}</td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.seats.edit', $seat),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.seats.destroy', $seat)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
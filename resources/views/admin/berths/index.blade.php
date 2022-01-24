@extends('layouts.admin')

@section('title', 'Berths')

@section('page-title', 'Berths')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.berths.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Berth
  </a>
@endsection

@section('page')
  @if($berths->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Number</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($berths as $berth)
        <tr>
          <td>{{ $berth->id }}</td>
          <td>{{ $berth->number }}</td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.berths.edit', $berth),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.berths.destroy', $berth)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
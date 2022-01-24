@extends('layouts.admin')

@section('title', 'Popups')

@section('page-title', 'Popups')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'pop up',
    'url'  => routeWithCurrentUrlAsRedirect('admin.pop-ups.create')
  ])
@endsection

@section('page')
  @include('admin.pop-ups._filter-form')

  @if ($popUps->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <tr>
          <th></th>
          <th class="text-left">Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($popUps as $popUp)
          <tr>
            <td class="text-center">{{ $popUp->id }}</td>
            <td>{{ $popUp->name }}</td>
            <td>
              @include('admin._partials.table-row-action-cell', [
                'edit' => routeWithCurrentUrlAsRedirect('admin.pop-ups.edit', $popUp),
                'destroy' => routeWithCurrentUrlAsRedirect('admin.pop-ups.destroy', $popUp)
              ])
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $popUps->links() }}
  @endif
@endsection
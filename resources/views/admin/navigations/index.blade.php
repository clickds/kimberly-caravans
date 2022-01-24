@extends('layouts.admin')

@section('title', 'Navigations')

@section('page-title', 'Navigations')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'navigation',
    'url'  => routeWithCurrentUrlAsRedirect('admin.navigations.create')
  ])
@endsection

@section('page')
  @include('admin.navigations._filter-form', [
    'types' => $types,
    'sites' => $sites,
  ])

  @if($navigations->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Site</td>
        <td>Type</td>
        <td>Items</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($navigations as $navigation)
        <tr>
          <td>{{ $navigation->id }}</td>
          <td>{{ $navigation->name }}</td>
          <td>{{ $navigation->site->country }}</td>
          <td>{{ $navigation->type }}</td>
          <td>
            <a href="{{ route('admin.navigations.navigation-items.index', ['navigation' => $navigation]) }}">
              {{ $navigation->navigation_items_count }}
            </a>
          </td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.navigations.edit', [$navigation->id]),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.navigations.destroy', [$navigation->id])])
          </td>
        </tr>
      @endforeach
    </table>
  @endif

@endsection
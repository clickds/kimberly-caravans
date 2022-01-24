@extends('layouts.admin')

@section('title', 'Useful Link Categories')

@section('page-title', 'Useful Link Categories')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'useful link category',
    'url' => routeWithCurrentUrlAsRedirect('admin.useful-link-categories.create')
  ])
@endsection

@section('page')
  @if($usefulLinkCategories->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($usefulLinkCategories as $usefulLinkCategory)
        <tr>
          <td>{{ $usefulLinkCategory->id }}</td>
          <td>{{ $usefulLinkCategory->name }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.useful-link-categories.edit', $usefulLinkCategory),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.useful-link-categories.destroy', $usefulLinkCategory)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
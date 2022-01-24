@extends('layouts.admin')

@section('title', 'Navigation Items')

@section('page-title', 'Navigation Items')

@section('page-actions')
  @include('admin._partials.page-actions', [
      'name' => 'navigation item',
      'url'  => route('admin.navigations.navigation-items.create', ['navigation' => $navigation])
  ])
@endsection

@section('page')
  @if($navigationItems->isNotEmpty())
    <table class="admin-table w-full my-5">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Display Order</td>
        <td>Parent</td>
        <td>Page</td>
        <td>External URL</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($navigationItems as $navigationItem)
        <tr>
          <td>{{ $navigationItem->id }}</td>
          <td>{{ $navigationItem->name }}</td>
          <td>{{ $navigationItem->display_order }}</td>
          <td>{{ $navigationItem->parent ? $navigationItem->parent->name : '' }}</td>
          <td>{{ $navigationItem->page ? $navigationItem->page->name : '' }}</td>
          <td>{{ $navigationItem->external_url }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => route('admin.navigations.navigation-items.edit', [$navigation->id, $navigationItem->id]),
              'destroy' => route('admin.navigations.navigation-items.destroy', [$navigation->id, $navigationItem->id])])
          </td>
        </tr>
      @endforeach
    </table>

    <navigation-items-tree
        :errors='@json($errors->getMessages(), JSON_FORCE_OBJECT)'
        :initial-navigation-items='@json($navigationItemsTree)'
        url="{{ route('admin.navigations.navigation-items-hierarchy.update', ['navigation' => $navigation]) }}"
        csrf-token="{{ csrf_token() }}"
    />
  @endif
@endsection
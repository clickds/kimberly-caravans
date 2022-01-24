@extends('layouts.admin')

@section('title', 'Aliases')

@section('page-title', 'Aliases')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.aliases.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    New Alias
  </a>
@endsection

@section('page')
  @include('admin.aliases._filter-form', [
    'sites' => $sites,
  ])

  @if($aliases->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>Capture Path</td>
        <td>Page To Redirect To</td>
        <td>Site</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($aliases as $alias)
        <tr>
          <td>{{ $alias->capture_path }}</td>
          <td>{{ $alias->page->name }}</td>
          <td>
            {{ $alias->site->country }}
          </td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.aliases.edit', $alias),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.aliases.destroy', $alias)
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $aliases->links() }}
  @endif
@endsection
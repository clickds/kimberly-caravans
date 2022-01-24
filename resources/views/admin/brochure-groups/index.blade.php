@extends('layouts.admin')

@section('title', 'Brochures Groups')

@section('page-title', 'Brochures Groups')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.brochure-groups.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Brochure Group</a>
@endsection

@section('page')
  @include('admin.brochure-groups._filter-form')

  @if($brochure_groups->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($brochure_groups as $brochureGroup)
        <tr>
          <td>{{ $brochureGroup->id }}</td>
          <td>{{ $brochureGroup->name }}</td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.brochure-groups.edit', $brochureGroup),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.brochure-groups.destroy', $brochureGroup)
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $brochure_groups->links() }}

  @endif
@endsection
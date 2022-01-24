@extends('layouts.admin')

@section('title', 'Brochures')

@section('page-title', 'Brochures')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.brochures.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Brochure</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Brochures Page'])
@endsection

@section('page')
  @include('admin.brochures._filter-form', [
    'brochureGroups' => $brochureGroups,
  ])

  @if($brochures->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Title</td>
        <td>Site</td>
        <td>Group</td>
        <td>Published At</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($brochures as $brochure)
        <tr>
          <td>{{ $brochure->id }}</td>
          <td>{{ $brochure->title }}</td>
          <td>{{ $brochure->site->country }}</td>
          <td>{{ $brochure->group->name ?? '' }}</td>
          <td>{{ $brochure->published_at ? $brochure->published_at->format('d/m/Y') : '' }}</td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.brochures.edit', $brochure),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.brochures.destroy', $brochure)
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $brochures->links() }}

  @endif
@endsection
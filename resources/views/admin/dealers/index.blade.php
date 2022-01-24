@extends('layouts.admin')

@section('title', 'Dealers')

@section('page-title', 'Dealers')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.dealers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Dealer</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Dealers Page'])
@endsection

@section('page')
  @include('admin.dealers._filter-form')

  @if($dealers->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Site</td>
        <td>Branch</td>
        <td>Servicing Center</td>
        <td>Can View Motorhomes</td>
        <td>Can View Caravans</td>
        <td>Employees</td>
        <td>Published</td>
        <td>Pages</td>
        <td>Actions</td>
      </thead>
      @foreach($dealers as $dealer)
        <tr>
          <td>{{ $dealer->id }}</td>
          <td>{{ $dealer->name }}</td>
          <td>{{ $dealer->site->country }}</td>
          <td>{{ $dealer->is_branch ? 'Y' : 'N' }}</td>
          <td>{{ $dealer->is_servicing_center ? 'Y' : 'N' }}</td>
          <td>{{ $dealer->can_view_motorhomes ? 'Y' : 'N' }}</td>
          <td>{{ $dealer->can_view_caravans ? 'Y' : 'N' }}</td>
          <td>
            <a href="{{ route('admin.dealers.employees.index', $dealer) }}">
              @choice('dealers.employees', $dealer->employees_count)
            </a>
          </td>
          <td>{{ $dealer->published_at ? $dealer->published_at->format('d-m-Y') : '' }}</td>
          <td>
            @foreach ($dealer->pages as $page)
              <a href="{{ $page->link() }}" class="block" target="_blank">{{ $page->slug }}</a>
            @endforeach
          </td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.dealers.edit', $dealer),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.dealers.destroy', $dealer)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
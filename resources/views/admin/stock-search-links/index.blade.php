@extends('layouts.admin')

@section('title', 'Stock Search Links')

@section('page-title', 'Stock Search Links')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'stock search link',
    'url'  => routeWithCurrentUrlAsRedirect('admin.stock-search-links.create')
  ])
@endsection

@section('page')
  @if($stockSearchLinks->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Image</td>
        <td>Mobile Image</td>
        <td>Name</td>
        <td>Type</td>
        <td>Site</td>
        <td>Page</td>
        <td>Actions</td>
      </thead>
      @foreach($stockSearchLinks as $stockSearchLink)
        <tr>
          <td>{{ $stockSearchLink->id }}</td>
          <td>
            @if ($url = $stockSearchLink->getFirstMediaUrl('image', 'thumb'))
              <img src="{{ $url }}" />
            @endif
          </td>
          <td>
            @if ($url = $stockSearchLink->getFirstMediaUrl('mobile-image', 'thumb'))
              <img src="{{ $url }}" />
            @endif
          </td>
          <td>{{ $stockSearchLink->name }}</td>
          <td>{{ $stockSearchLink->type }}</td>
          <td>{{ $stockSearchLink->site->country }}</td>
          <td>{{ $stockSearchLink->page->name }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.stock-search-links.edit', $stockSearchLink),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.stock-search-links.destroy', $stockSearchLink),
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
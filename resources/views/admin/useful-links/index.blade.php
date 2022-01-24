@extends('layouts.admin')

@section('title', 'Useful Links')

@section('page-title', 'Useful Links')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'useful link',
    'url'  => routeWithCurrentUrlAsRedirect('admin.useful-links.create')
  ])
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Useful Links Page'])
@endsection

@section('page')
  @include('admin.useful-links._filter-form', [
    'categories' => $categories,
  ])

  @if($usefulLinks->isNotEmpty())
    <table class="admin-table">
      <thead>
        <td>ID</td>
        <td>Image</td>
        <td>Name</td>
        <td>Category</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($usefulLinks as $usefulLink)
        <tr>
          <td>{{ $usefulLink->id }}</td>
          <td>
            @if ($url = $usefulLink->getFirstMediaUrl('image', 'thumb'))
              <img src="{{ $url }}">
            @endif
          </td>
          <td>{{ $usefulLink->name }}</td>
          <td>{{ $usefulLink->categoryName() }}</td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit'    => routeWithCurrentUrlAsRedirect('admin.useful-links.edit', $usefulLink),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.useful-links.destroy', $usefulLink)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
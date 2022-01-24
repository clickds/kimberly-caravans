@extends('layouts.admin')

@section('title', 'Review Categories')

@section('page-title', 'Review Categories')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.review-categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Review Category</a>
@endsection

@section('page')
  @if($reviewCategories->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Name</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($reviewCategories as $reviewCategory)
        <tr>
          <td>{{ $reviewCategory->id }}</td>
          <td>{{ $reviewCategory->name }}</td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.review-categories.edit', $reviewCategory),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.review-categories.destroy', $reviewCategory)
            ])
          </td>
        </tr>
      @endforeach
    </table>
  @endif
@endsection
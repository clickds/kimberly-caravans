@extends('layouts.admin')

@section('title', 'Reviews')

@section('page-title', 'Reviews')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.reviews.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Review</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Reviews Page'])
@endsection

@section('page')
  @include('admin.reviews._filter-form', [
    'categories' => $categories,
  ])
  @if($reviews->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Title</td>
        <td>Category</td>
        <td>Magazine</td>
        <td>Date</td>
        <td>Dealer</td>
        <td>Link</td>
        <td>Published At</td>
        <td>Expired At</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($reviews as $review)
        <tr>
          <td>{{ $review->id }}</td>
          <td>{{ $review->title }}</td>
          <td>{{ isset($review->category) ? $review->category->name : 'None' }}</td>
          <td>{{ $review->magazine }}</td>
          <td>{{ $review->date->format('d/m/Y') }}</td>
          <td>{{ $review->dealerName() }}</td>
          <td>
            <a href="{{ $review->link }}">
              View
            </a>
          </td>
          <td>{{ $review->published_at ? $review->published_at->format('d/m/Y') : '' }}</td>
          <td>{{ $review->expired_at ? $review->expired_at->format('d/m/Y') : '' }}</td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.reviews.edit', $review),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.reviews.destroy', $review)
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $reviews->links() }}

  @endif
@endsection
@extends('layouts.admin')

@section('title', 'Videos')

@section('page-title', 'Videos')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.videos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Video</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Videos Page'])
@endsection

@section('page')
  @include('admin.videos._filter-form', [
    'categories' => $categories,
  ])

  @if($videos->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Title</td>
        <td>Published At</td>
        <td>Pages</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($videos as $video)
        <tr>
          <td>{{ $video->id }}</td>
          <td>{{ $video->title }}</td>
          <td>{{ $video->published_at->format('d-m-Y') }}</td>
          <td>
            @foreach ($video->pages as $page)
              <a href="{{ $page->link() }}" class="block" target="_blank">{{ $page->slug }}</a>
            @endforeach
          </td>
          <td class="text-center">
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.videos.edit', $video),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.videos.destroy', $video)
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $videos->links() }}

  @endif
@endsection
@extends('layouts.admin')

@section('title', 'Video banners')

@section('page-title', 'Video banners')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'video banner',
    'url'  => routeWithCurrentUrlAsRedirect('admin.video-banners.create')
  ])
@endsection

@section('page')
  @include('admin.video-banners._filter-form')

  @if ($videoBanners->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td></td>
        <td class="text-left">Name</td>
        <td>Published At</td>
        <td>Expired At</td>
        <td>Live</td>
        <td>Pages</td>
        <td>Actions</td>
      </thead>
      <tbody>
        @foreach ($videoBanners as $videoBanner)
          <tr>
            <td class="text-center">{{ $videoBanner->id }}</td>
            <td>{{ $videoBanner->name }}</td>
            <td>{{ $videoBanner->published_at ? $videoBanner->published_at->format('d-m-Y') : 'N/A' }}</td>
            <td>{{ $videoBanner->expired_at ? $videoBanner->expired_at->format('d-m-Y') : 'N/A' }}</td>
            <td>{{ true === $videoBanner->live ? 'Y' : 'N' }}</td>
            <td>
              <ul class="list-disc">
                @foreach($videoBanner->pages as $page)
                  <li>
                    <a href="{{ $page->siteLink() }}">
                      {{ $page->name }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </td>
            <td>
              @include('admin._partials.table-row-action-cell', [
                'edit' => routeWithCurrentUrlAsRedirect('admin.video-banners.edit', $videoBanner),
                'destroy' => routeWithCurrentUrlAsRedirect('admin.video-banners.destroy', $videoBanner)
              ])
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $videoBanners->links() }}
  @endif
@endsection
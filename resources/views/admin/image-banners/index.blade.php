@extends('layouts.admin')

@section('title', 'Image Banners')

@section('page-title', 'Image Banners')

@section('page-actions')
  @include('admin._partials.button', [
    'url' => routeWithCurrentUrlAsRedirect('admin.image-banners.create'),
    'text' => 'New Image Banner',
  ])
@endsection

@section('page')
  @include('admin.image-banners._filter-form')

  @if($imageBanners->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <td>ID</td>
        <td>Image</td>
        <td>Title</td>
        <td>Published At</td>
        <td>Expired At</td>
        <td>Live</td>
        <td>Pages</td>
        <td>Buttons</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($imageBanners as $imageBanner)
        <tr>
          <td>{{ $imageBanner->id }}</td>
          <td>
            @if ($media = $imageBanner->getFirstMedia('image'))
              <img src="{{ $media->getUrl('thumb') }}" alt="{{ $media->name }}">
            @endif
          </td>
          <td>{{ $imageBanner->title }}</td>
          <td>{{ $imageBanner->published_at ? $imageBanner->published_at->format('d-m-Y') : 'N/A' }}</td>
          <td>{{ $imageBanner->expired_at ? $imageBanner->expired_at->format('d-m-Y') : 'N/A' }}</td>
          <td>{{ $imageBanner->live ? 'Y' : 'N' }}</td>
          <td>
            <ul class="list-disc">
              @foreach($imageBanner->pages as $page)
                <li>
                  <a href="{{ $page->siteLink() }}">
                    {{ $page->name }}
                  </a>
                </li>
              @endforeach
            </ul>
          </td>
          <td>
            <a href="{{ route('admin.image-banners.buttons.index', $imageBanner) }}">
              @choice('global.buttons', $imageBanner->buttons_count)
            </a>
          </td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.image-banners.edit', $imageBanner),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.image-banners.destroy', $imageBanner)
            ])
          </td>
        </tr>
      @endforeach
    </table>

    {{ $imageBanners->links() }}
  @endif
@endsection
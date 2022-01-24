@extends('layouts.admin')

@section('title', 'Assets')

@section('page-title', 'Assets')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'asset',
    'url'  => routeWithCurrentUrlAsRedirect('admin.assets.create')
  ])
@endsection

@section('page')
  @include('admin.assets._filter-form')

  @if($assets->isNotEmpty())
    <table class="admin-table">
        <thead>
            <td>ID</td>
            <td>Image</td>
            <td>Name</td>
            <td>Url</td>
            <td class="text-center">Actions</td>
        </thead>
        @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->id }}</td>
                <td>
                  @if ($media = $asset->getFirstMedia('file'))
                    @if ($media->hasGeneratedConversion('thumb'))
                      <img src="{{ $media->getUrl('thumb') }}">
                    @endif
                  @endif
                </td>
                <td>{{ $asset->name }}</td>
                <td>
                  @if ($url = $asset->getFirstMediaUrl('file'))
                    <a href="{{ $url }}">{{ $url }}</a>
                  @endif
                </td>
                <td>
                    @include('admin._partials.table-row-action-cell', [
                      'destroy' => routeWithCurrentUrlAsRedirect('admin.assets.destroy', $asset)
                    ])
                </td>
            </tr>
        @endforeach
    </table>

    {{ $assets->withQueryString()->links() }}
  @endif
@endsection
@extends('layouts.admin')

@section('title', 'All Pages')

@section('page-title', 'All Pages')

@section('page-actions')
<a href="{{ routeWithCurrentUrlAsRedirect('admin.pages.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Page</a>
@endsection

@section('page')
  @include('admin.pages._filter-form', [
    'sites' => $sites,
    'templates' => $templates,
    'varieties' => $varieties,
  ])

  @if($pages->isNotEmpty())
    <table class="admin-table mt-5 w-full">
      <thead>
        <td>ID</td>
        <td>Site</td>
        <td>Name</td>
        <td>Template</td>
        <td>Slug</td>
        <td class="text-center">Areas</td>
        <td class="text-center">Actions</td>
      </thead>
      @foreach($pages as $page)
        <tr>
          <td>{{ $page->id }}</td>
          <td>{{ $page->site->country }}</td>
          <td>{{ $page->name }}</td>
          <td>{{ $page->templateName() }}
          <td>
            <a href="//{{ $page->siteLink() }}" target="_blank" rel="noopener" rel="noreferrer">
              {{ $page->slug }}
            </a>
          </td>
          <td class="text-center">
            <a href="{{ route('admin.pages.areas.index', ['page' => $page->id]) }}" class="admin-link">
              {{ $page->areas_count }}
            </a>
          </td>
          <td class="flex justify-center">
            @if($page->isCloneable())
              @include('admin._partials.table-row-action-cell', [
                'show' => routeWithCurrentUrlAsRedirect('admin.pages.show', $page),
                'edit' => routeWithCurrentUrlAsRedirect('admin.pages.edit', $page),
                'destroy' => routeWithCurrentUrlAsRedirect('admin.pages.destroy', $page),
                'additional' => [
                  [
                    'text' => trans('global.clone'),
                    'url' => routeWithCurrentUrlAsRedirect('admin.pages.clones.create', ['page' => $page]),
                  ],
                ],
              ])
            @else
              @include('admin._partials.table-row-action-cell', [
                'show' => routeWithCurrentUrlAsRedirect('admin.pages.show', $page),
                'edit' => routeWithCurrentUrlAsRedirect('admin.pages.edit', $page),
                'destroy' => routeWithCurrentUrlAsRedirect('admin.pages.destroy', $page),
              ])
            @endif
          </td>
        </tr>
      @endforeach
    </table>

    {{ $pages->appends(request()->all())->links() }}
  @endif
@endsection
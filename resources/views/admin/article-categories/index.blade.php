@extends('layouts.admin')

@section('title', 'Article Categories')

@section('page-title', 'Article Categories')

@section('page-actions')
  @include('admin._partials.page-actions', [
    'name' => 'article category',
    'url'  => routeWithCurrentUrlAsRedirect('admin.article-categories.create')
  ])
@endsection

@section('page')
  @if($articleCategories->isNotEmpty())
    <table class="admin-table">
        <thead>
            <td>ID</td>
            <td>Name</td>
            <td class="text-center">Actions</td>
        </thead>
        @foreach($articleCategories as $articleCategory)
            <tr>
                <td>{{ $articleCategory->id }}</td>
                <td>{{ $articleCategory->name }}</td>
                <td>
                    @include('admin._partials.table-row-action-cell', [
                      'edit' => routeWithCurrentUrlAsRedirect('admin.article-categories.edit', $articleCategory),
                      'destroy' => routeWithCurrentUrlAsRedirect('admin.article-categories.destroy', $articleCategory)
                    ])
                </td>
            </tr>
        @endforeach
    </table>
  @endif
@endsection
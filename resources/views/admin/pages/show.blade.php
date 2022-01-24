@extends('layouts.admin')

@section('title', 'View Page')

@section('page-title', 'View Page')

@section('page-actions')
  <a href="{{ route('admin.pages.edit', ['page' => $page->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit Page</a>
  <form method="POST" action="{{ route('admin.pages.destroy', ['page' => $page->id]) }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete Page</button>
  </form>
@endsection

@section('page')
  <div class="w-1/2">
    <dl class="admin-description-list">
      <dt>ID</dt>
      <dd>{{ $page->id }}</dd>
      <dt>Name</dt>
      <dd>{{ $page->name }}</dd>
      <dt>Slug</dt>
      <dd>{{ $page->slug }}</dd>
      <dt>Site</dt>
      <dd>{{ $page->site->country }}</dd>
      <dt>Parent Page</dt>
      <dd>
        {{ $page->hasParent() ? $page->parent->name : 'None' }}
      </dd>
      <dt>Published At</dt>
      <dd>
        {{ $page->hasPublishedAtDate() ? $page->published_at : 'None' }}
      </dd>
      <dt>Expired At</dt>
      <dd>
        {{ $page->hasExpiredAtDate() ? $page->expired_at : 'None' }}
      </dd>
      <dt>Areas</dt>
      <dd>
        <a href="{{ route('admin.pages.areas.index', ['page' => $page->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View Areas ({{ $page->areas->count() }})</a>
      </dd>
    </dl>
  </div>
@endsection

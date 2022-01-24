@extends('layouts.admin')

@section('title', 'Articles')

@section('page-title', 'Articles')

@section('page-actions')
  <a href="{{ routeWithCurrentUrlAsRedirect('admin.articles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Article</a>
  @include('admin._partials.listing-page-links', ['listingPages' => $listingPages, 'buttonText' => 'View Articles Page'])
@endsection

@section('page')
  @include('admin.articles._filter-form', [
    'categories' => $categories,
  ])

  @if($articles->isNotEmpty())
    <admin-table
      :headings="['ID', 'Title', 'Published', 'Status', 'Pages', 'Actions']"
      :enable-bulk-delete="true"
      bulk-delete-url="{{ route('admin.articles.bulk-delete') }}"
      bulk-delete-input-name="article_ids"
      :record-ids='@json($articles->pluck("id")->toArray())'
    >
      <template #delete-method>
        @method('delete')
      </template>
      <template #csrf>
        @csrf
      </template>
      @foreach($articles as $article)
        <template #row-cells-{{ $article->id }}>
          <td>{{ $article->id }}</td>
          <td>{{ $article->title }}</td>
          <td>{{ $article->publishedDate() }}</td>
          <td>{{ $article->publishedStatus() }}</td>
          <td>
            @foreach ($article->pages as $page)
              <a href="{{ $page->link() }}" class="block" target="_blank">{{ $page->slug }}</a>
            @endforeach
          </td>
          <td>
            @include('admin._partials.table-row-action-cell', [
              'edit' => routeWithCurrentUrlAsRedirect('admin.articles.edit', $article),
              'destroy' => routeWithCurrentUrlAsRedirect('admin.articles.destroy', $article)
            ])
          </td>
        </template>
      @endforeach
    </admin-table>
    {{ $articles->links() }}
  @endif
@endsection
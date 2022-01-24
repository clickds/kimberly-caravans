<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Showing search results for "{{ $pageFacade->getSearchTerm() }}"</h1>
  </div>
</div>

@include('site.shared.listing-pages.top-pagination', [
  'paginator' => $pageFacade->getSearchResultsPaginator()
])

<div>
  <search-filters
    :categories='@json($pageFacade->getCategories())'
    :per-page='{{ $pageFacade->getPerPage() }}'
    query="{{ $pageFacade->getSearchTerm() }}"
  ></search-filters>
</div>

@include('site.pages.search._collection')

@include('site.shared.listing-pages.bottom-pagination', [
  'paginator' => $pageFacade->getSearchResultsPaginator(),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])
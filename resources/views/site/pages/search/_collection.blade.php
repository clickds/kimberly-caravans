<div class="bg-gallery py-4 md:py-10">
  <div class="container mx-auto px-standard">
    @if ($pageFacade->getSearchResults())
      @foreach ($pageFacade->getSearchResults() as $searchResult)
        @include('site.pages.search._search-result', ['searchResult' => $searchResult])
      @endforeach
    @else
      No results found.
    @endif
  </div>
</div>
{{ $pageFacade->getSpecialOffer()->name }}

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

<stock-search-app initial-stock-type="caravan"
  url="{{ $pageFacade->getSearchUrl() }}"
  :initial-filters="{{ json_encode($pageFacade->getFilters()) }}"
  :stock-search-links="{{ json_encode($pageFacade->getStockSearchLinks()) }}">
</stock-search-app
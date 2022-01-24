<stock-search-app initial-stock-type="motorhome"
  url="{{ $pageFacade->getSearchUrl() }}"
  :initial-filters="{{ json_encode($pageFacade->getFilters()) }}"
  :stock-search-links="{{ json_encode($pageFacade->getStockSearchLinks()) }}">
</stock-search-app>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])
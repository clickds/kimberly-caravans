<used-range-stock-items
  api-search-url="{{ $apiSearchUrl }}"
  range-name="{{ $pageFacade->getRange()->name }}"
  range-filter='{{ $pageFacade->getRangeFilter() }}'
  used-stock-filter='{{ $pageFacade->getUsedStockFilter() }}'
  search-page-url='{{ $pageFacade->getRangeUsedStockSearchPageUrl() }}'
></used-range-stock-items>
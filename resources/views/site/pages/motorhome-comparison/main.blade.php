@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

<motorhome-comparison-app motorhome-stock-search-page-url={{ $pageFacade->getMotorhomeStockSearchPageUrl() }}>
</motorhome-comparison-app>

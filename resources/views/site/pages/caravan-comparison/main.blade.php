@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

<caravan-comparison-app caravan-stock-search-page-url={{ $pageFacade->getCaravanStockSearchPageUrl() }}></caravan-comparison-app>

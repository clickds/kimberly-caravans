@include('site.pages.video._main-details', [
  'video' => $pageFacade->getVideo(),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

@include('site.shared.areas-for-tabbed-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Tabbed',
  'areas' => $pageFacade->getAreas('Tabbed'),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Secondary',
  'areas' => $pageFacade->getAreas('Secondary'),
])
@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

@include('site.pages.vacancies-listing._collection', ['vacancies' => $pageFacade->getVacancies()])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Secondary',
  'areas' => $pageFacade->getAreas('Secondary'),
])
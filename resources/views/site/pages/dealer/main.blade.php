@include('site.pages.dealer._details-map-video-embed-grid', ['dealer' => $pageFacade->getDealer()])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

@include('site.pages.dealer._facilities-and-services', ['dealer' => $pageFacade->getDealer()])

<managers-specials
  caravan-search-url='{{ $pageFacade->getCaravanManagersSpecialsSearchUrl() }}'
  motorhome-search-url='{{ $pageFacade->getMotorhomeManagersSpecialsSearchUrl() }}'
  :dealer-id='{{ $pageFacade->getDealer()->id }}'
></managers-specials>

@include('site.pages.dealer._dealer-employees', ['dealer' => $pageFacade->getDealer()])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Secondary',
  'areas' => $pageFacade->getAreas('Secondary'),
])
@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

@foreach($pageFacade->getSpecialOffers() as $specialOffer)
  @include('special-offers._special-offer', [
    'specialOffer' => $specialOffer,
  ])
@endforeach
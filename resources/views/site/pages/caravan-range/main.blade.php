@include('site.shared.vehicle-range-page.main-image', [
  'media' => $pageFacade->getHeaderImage(),
  'rangeName' => $pageFacade->bannerTitle(),
  'range' => $pageFacade->getRange(),
])

@include('site.pages.caravan-range._tabs', [
  'pageFacade' => $pageFacade,
])

@include('site.shared.vehicle-range-slider', [
  'ranges' => $pageFacade->getOtherRangesByManufacturer(),
  'site' => $pageFacade->getSite(),
])
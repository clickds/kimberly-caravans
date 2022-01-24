<range-technical-tab
  :models='@json($pageFacade->getFormattedCaravansForVue())'
  :range-specification-small-prints='@json($pageFacade->getSpecificationSmallPrints())'
  vehicle-type="caravan"
>
  @include('site.shared.areas-for-holder', [
    'page' => $pageFacade->getPage(),
    'holder' => 'Technical',
    'areas' => $pageFacade->getAreas('Technical'),
  ])
</range-technical-tab>
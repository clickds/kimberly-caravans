<range-technical-tab
  :models='@json($pageFacade->getFormattedMotorhomesForVue())'
  :range-specification-small-prints='@json($pageFacade->getSpecificationSmallPrints())'
  vehicle-type="motorhome"
>
  @include('site.shared.areas-for-holder', [
    'page' => $pageFacade->getPage(),
    'holder' => 'Technical',
    'areas' => $pageFacade->getAreas('Technical'),
  ])
</range-technical-tab>
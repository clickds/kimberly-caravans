<section>
  <h2 class="text-center text-4xl text-endeavour font-semibold py-8">
    Browse our new motorhomes
  </h2>

  @include('site.shared.new-vehicle-tabs', [
    'allManufacturerPages' => $pageFacade->getManufacturerPages(),
    'exclusivePages' => $pageFacade->getExclusivePages(),
    'otherManufacturerPages' => $pageFacade->getOtherManufacturerPages(),
    'tabNames' => $pageFacade->getTabNames(),
  ])
</section>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])
<div class="w-full container mx-auto px-standard py-4">
  @include('site.shared.areas-for-holder', [
    'page' => $pageFacade->getPage(),
    'holder' => 'Offers',
    'areas' => $pageFacade->getAreas('Offers'),
  ])

  @include('site.shared.vehicle-range-page.used-range-stock-items', [
    'apiSearchUrl' => route('api.caravan-stock-items.search')
  ])

  @include('site.shared.vehicle-range-page.newsletter-sign-up-form', ['form' => $newsletterForm])
</div>
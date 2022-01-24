<section class="component">
  <tabs
    :show-secondary-navigation="true"
    :tabs='{{ json_encode($pageFacade->tabNames()) }}'
    :sticky="true"
    offset-top-element-id="js-navigation-and-comparison-container"
  >
    <template #models>
      @include('site.pages.caravan-range.tabs._models', [
        'pageFacade' => $pageFacade,
      ])
    </template>
    <template #features>
      @include('site.pages.caravan-range.tabs._features', [
        'pageFacade' => $pageFacade,
      ])
    </template>
    <template #technical>
      @include('site.pages.caravan-range.tabs._technical', [
        'pageFacade' => $pageFacade,
      ])
    </template>
    <template #buy>
      @include('site.pages.caravan-range.tabs._buy', [
        'pageFacade' => $pageFacade,
        'site' => $pageFacade->getSite(),
      ])
    </template>
    <template #gallery>
      @include('site.pages.caravan-range.tabs._gallery', [
        'pageFacade' => $pageFacade,
        'interiorImages' => $pageFacade->imagesForGallery('interiorGallery'),
        'exteriorImages' => $pageFacade->imagesForGallery('exteriorGallery'),
        'featureImages' => $pageFacade->imagesForGallery('featureGallery'),
      ])
    </template>
    @if (in_array('videos and reviews', $pageFacade->tabNames()))
      <template #videos-and-reviews>
        @include('site.pages.caravan-range.tabs._videos-and-reviews', [
          'pageFacade' => $pageFacade,
        ])
      </template>
    @endif
    @if (in_array('brochures', $pageFacade->tabNames()))
      <template #brochures>
        @include('site.pages.caravan-range.tabs._brochures', [
          'pageFacade' => $pageFacade,
          'brochures' => $pageFacade->getBrochures(),
        ])
      </template>
    @endif
    <template #offers>
      @include('site.pages.caravan-range.tabs._offers', [
        'pageFacade' => $pageFacade,
        'specialOffers' => $pageFacade->getSpecialOffers(),
        'newsletterForm' => $pageFacade->getNewsletterSignUpForm(),
      ])
    </template>
    <template #dealers>
      @include('site.pages.caravan-range.tabs._dealers', [
        'pageFacade' => $pageFacade,
        'dealers' => $pageFacade->getRangeDealers(),
      ])
    </template>
  </tabs>
</section>
@if ($specialOfferPages->isNotEmpty())
  <div class="my-4 container mx-auto px-standard">
    <div class="stock-item-special-offer-slider">
      <div class="control-previous px-1 flex flex-col justify-center bg-gallery" tabindex="-1">
        <i class="fas fa-chevron-left" aria-label="hidden"></i>
      </div>
      <div class="slides-container">
        @foreach ($specialOfferPages as $specialOfferPage)
          @include('site.shared.stock-item-page.special-offer-slide', [
            'specialOfferPage' => $specialOfferPage,
            'specialOffer' => $specialOfferPage->pageable,
          ])
        @endforeach
      </div>
      <div class="control-next px-1 flex flex-col justify-center bg-gallery" tabindex="-1">
        <i class="fas fa-chevron-right" aria-label="hidden"></i>
      </div>
    </div>
  </div>
@endif
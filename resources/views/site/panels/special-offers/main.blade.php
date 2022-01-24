<div class="special-offer-slider h-full">
  <div class="controls flex">
    <div class="order-1 control" aria-label="Previous slide">
      <i class="fas fa-chevron-left"></i>
    </div>
    <div class="order-3 control" aria-label="Next slide">
      <i class="fas fa-chevron-right"></i>
    </div>
    <ul class="navigation order-2">
      @foreach ($panel->getSpecialOffers() as $specialOffer)
        <li data-nav="{{ $loop->index }}" aria-label="Special Offer Slider Page {{ $loop->iteration }}"
          aria-controls="special-offer-slider-{{ $panel->id }}" class="" tabindex="-1">
        </li>
      @endforeach
    </ul>
  </div>
  <div id="special-offer-slider-{{ $panel->id }}" class="slides-container flex items-stretch">
    @foreach ($panel->getSpecialOffers() as $specialOffer)
      @include('site.panels.special-offers._special-offer', [
        'specialOffer' => $specialOffer,
        'imageName' => $panel->areaColumns() == 2 ? 'squareImage' : 'landscapeImage',
      ])
    @endforeach
  </div>
</div>
<div class="slide">
  <a class="block flex justify-center" href="{{ $specialOfferPage->link() }}">
    <div class="block md:hidden">
      @if($image = $specialOffer->squareImage())
        {{ $image('responsive') }}
      @endif
    </div>
    <div class="hidden md:block">
      @if($image = $specialOffer->landscapeImage())
        {{ $image('responsive') }}
      @endif
    </div>
  </a>
</div>
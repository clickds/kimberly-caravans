<div class="slide text-white bg-regal-blue">
  <div class="relative image-object-cover image-object-center w-full">
    @if($image = $specialOffer->getFirstMedia($imageName))
      {{ $image('responsive') }}
    @endif
    <div class="hidden absolute bottom-0 w-full grid grid-cols-2 gap-10 px-3 pb-10 md:grid">
      @foreach ($specialOffer->pages as $specialOfferPage)
        <a class="button button-shiraz w-full md:w-auto text-center" href="{{ $specialOfferPage->link() }}">
          {{ $specialOfferPage->linkText() }}
        </a>
      @endforeach
    </div>
  </div>
  <div class="bg-silver-gray w-full flex flex-wrap items-center justify-around p-4 md:hidden">
    @foreach ($specialOffer->pages as $specialOfferPage)
      <a class="button button-shiraz w-full md:w-auto" href="{{ $specialOfferPage->link() }}">
        {{ $specialOfferPage->linkText() }}
      </a>
    @endforeach
  </div>
</div>
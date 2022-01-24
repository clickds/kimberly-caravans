@if ($image = $specialOffer->landscapeImage())
<article class="special-offer mb-4 md:mb-8">
  <div class="container mx-auto px-standard">
    <div class="flex flex-wrap items-center -mx-2">
      <div class="order-first md:order-none w-full">
        @if ($link = $specialOffer->linkUrl())
          <a href="{{ $link }}" class="w-full">
            <div class="image-center mb-4 md:mb-0">
              {{ $image('responsive') }}
            </div>
          </a>
        @else
          <div class="image-center mb-4 md:mb-0">
            {{ $image('responsive') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</article>
@endif
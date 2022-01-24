@if($ranges->isNotEmpty())
<section class="bg-silver-gray py-2 px-standard">
  <div class="container mx-auto">
    <div class="vehicle-range-slider px-3">
      <div class="controls flex">
        <div class="control" aria-label="Previous slide">
          <i class="fas fa-chevron-left"></i>
        </div>
        <div class="control" aria-label="Next slide">
          <i class="fas fa-chevron-right"></i>
        </div>
      </div>
      <div class="slides-container flex">
        @foreach ($ranges as $range)
          <article class="p-2">
            <div class="mb-2 md:mb-4 font-heading font-semibold text-center text-white text-2xl">
              {{ $range->name }}
            </div>
            @if ($image = $range->mainImage())
              <div class="image-center">
                {{ $image('show') }}
              </div>
            @endif
            @if ($sitePage = $range->sitePage($site->getWrappedObject()))
              <a class="mt-2 md:mt-4 border border-white hover:text-silver-gray hover:bg-white block font-heading font-semibold text-center text-white text-2xl" href="{{ pageLink($sitePage) }}">
                Explore the range
              </a>
            @endif
          </article>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif
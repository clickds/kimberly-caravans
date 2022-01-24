@include('site.shared.listing-pages.top-pagination', [
  'paginator' => $testimonials,
])
<div class="bg-white py-4 md:py-10">
  <div class="container mx-auto px-standard">
    @if ($testimonials->isNotEmpty())
      @foreach ($testimonials as $testimonial)
        <div class="w-full md:w-4/5 mx-auto mb-8 md:mb-16">
          <div class="flex items-center">
            <div class="h-8 lg:h-12 self-start text-endeavour">
              @include('site.shared.svg-icons.quote-marks')
            </div>
            <div class="px-2 text-center flex-grow">
              <div class="mb-4">
                {{ $testimonial->content }}
              </div>
              <div class="text-endeavour">
                {{ $testimonial->name }}
              </div>
            </div>
            <div class="h-8 lg:h-12 self-end transform rotate-180 text-endeavour">
              @include('site.shared.svg-icons.quote-marks')
            </div>
          </div>
        </div>
      @endforeach
    @else
        No testimonials to display.
    @endif
  </div>
</div>
@include('site.shared.listing-pages.bottom-pagination', [
  'paginator' => $testimonials,
])
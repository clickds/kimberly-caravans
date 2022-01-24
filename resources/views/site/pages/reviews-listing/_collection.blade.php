@include('site.shared.listing-pages.top-pagination', [
  'paginator' => $reviews,
])
<div class="bg-gallery py-4 md:py-10">
  <div class="container mx-auto px-standard">
    @if ($reviews->isNotEmpty())
      <div class="flex flex-wrap -mx-4">
        @foreach ($reviews as $review)
          <div class="px-4 mb-4 md:mb-8 w-full md:w-1/2 lg:w-1/3">
            @include('site.reviews._review', ['review' => $review])
          </div>
        @endforeach
      </div>

    @else
        No reviews to display.
    @endif
  </div>
</div>
@include('site.shared.listing-pages.bottom-pagination', [
  'paginator' => $reviews,
])
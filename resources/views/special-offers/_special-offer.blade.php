<article class="special-offer mb-4 md:mb-8">
  <div class="container mx-auto px-standard">
    <div class="flex flex-wrap items-center -mx-2">
      <div class="w-full md:w-1/2 px-2">
        <h2 class="text-endeavour font-semibold mb-4 md:mb-8">
          {{ $specialOffer->name }}
        </h2>

        <div class="wysiwyg mb-4 md:mb-8">
          {!! $specialOffer->content !!}
        </div>

        @include('special-offers._page-links', [
          'pages' => $specialOffer->pages,
        ])

      </div>
      <div class="order-first md:order-none w-full md:w-1/2 px-2">
        @if ($image = $specialOffer->squareImage())
          <div class="image-center mb-4 md:mb-0">
            {{ $image('responsive') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</article>
<div class="w-full bg-white">
  <div class="container mx-auto py-5 px-standard">
    <h1 class="py-3 md:py-6 text-center text-endeavour">
      {{ $pageFacade->getRange()->name }} Brochures
    </h1>
  </div>
  <div class="w-full bg-gallery">
    <div class="py-4 md:py-8 container mx-auto px-standard">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($brochures as $brochure)
          @include('site.shared.brochure', [
            'brochure' => $brochure,
          ])
        @endforeach
      </div>
    </div>
  </div>
</div>
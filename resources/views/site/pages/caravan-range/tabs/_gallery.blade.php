<div class="w-full">
  <div class="bg-white container mx-auto px-standard py-5">
    <h1 class="py-3 md:py-6 mb-5 text-center text-endeavour">{{ $pageFacade->getRange()->name }} Range Gallery</h1>
  </div>

  @if ($interiorImages->isNotEmpty())
    <div class="bg-gallery">
      <div class="container mx-auto px-standard py-4 md:py-8">
        <h2 class="font-semibold text-center mb-4">Interior</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
          @foreach($interiorImages as $image)
            <div>
              <a href="{{ $image->getUrl() }}" class="lightbox">
                {{ $image('responsiveGallery') }}
                <div class="text-lg py-2">
                  {{ $image->name }}
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  @if ($exteriorImages->isNotEmpty())
    <div class="bg-white">
      <div class="container mx-auto px-standard py-4">
        <h2 class="font-semibold text-center mb-4">Exterior</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
          @foreach($exteriorImages as $image)
            <div>
              <a href="{{ $image->getUrl() }}" class="lightbox">
                {{ $image('responsiveGallery') }}
                <div class="text-lg py-2">
                  {{ $image->name }}
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  @if ($featureImages->isNotEmpty())
    <div class="bg-gallery">
      <div class="container mx-auto px-standard py-4">
        <h2 class="font-semibold text-center mb-4">Feature</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
          @foreach($featureImages as $image)
            <div>
              <a href="{{ $image->getUrl() }}" class="lightbox">
                {{ $image('responsiveGallery') }}
                <div class="text-lg py-2">
                  {{ $image->name }}
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
</div>
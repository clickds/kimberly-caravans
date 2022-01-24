<div class="relative featured-image-container overflow-hidden border-b-2 border-white">
  <div class="image-object-cover image-object-center w-full h-full">
    @if($image = $panel->getFeaturedImage())
      {{ $image('responsive') }}
    @endif
  </div>
  <div class="z-10 lg:absolute h-full w-full top-0 left-0">
    <div class="relative h-full w-full">
      <div class="{{ $panel->leftOverlayPosition() ? 'left-content-box' : 'right-content-box' }} content-box">
        @if ($heading = $panel->getHeading())
          <h2 class="text-white font-heading">
            {{ $heading }}
          </h2>
        @endif
        <div class="text-white text-2xl font-heading">
          {{ $panel->getFeaturedImageContent() }}
        </div>
      </div>
    </div>
  </div>
</div>

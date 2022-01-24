<div class="synced-slider">
  <div class="main-slider flex">
    @if ($stockItem->videoUrl())
      <div>
        <div class="plyr-video">
          <iframe
            src="{{ $stockItem->videoUrl() }}"
            allowfullscreen
            allowtransparency
          ></iframe>
        </div>
      </div>
    @endif
    @foreach ($stockItem->photos() as $photo)
      <div>
        <div class="h-full flex flex-col justify-center">
          {{ $photo('responsiveStockSlider') }}
        </div>
      </div>
    @endforeach
    @if ($stockItem->floorplans()->isNotEmpty())
    <div>
      <div class="flex flex-col justify-around h-full">
        @if ($stockItem->hasFeedSource())
          @foreach($stockItem->floorplans() as $floorplan)
            <img src="{{ $floorplan->getFullUrl() }}" alt="{{ $floorplan->name }}">
          @endforeach
        @else
          @foreach($stockItem->floorplans() as $floorplan)
            <div class="p-4 image-center">
              {{ $floorplan('show') }}
            </div>
          @endforeach
        @endif
      </div>
    </div>
  @endif
  </div>
  <div class="nav-slider-container">
    <div class="control control-previous" tabindex="-1">
      <i class="fas fa-chevron-left" aria-label="hidden"></i>
    </div>
    <div class="nav-slider flex-grow flex">
      @if ($stockItem->videoUrl())
        <div>
          <div class="h-full flex justify-center items-center text-shiraz">
            <img src="{{ $stockItem->videoThumbnail() }}" alt="Video">
          </div>
        </div>
      @endif
      @foreach ($stockItem->photos() as $photo)
        <div>
          <div class="h-full flex items-center">
            <img src="{{ $photo->getUrl('thumbStockSlider') }}" alt="{{ $photo->name }}">
          </div>
        </div>
      @endforeach
      @if ($stockItem->floorplans()->isNotEmpty())
      <div>
        <div class="h-full flex flex-col justify-around h-full">
          @if ($stockItem->hasFeedSource())
            @foreach($stockItem->floorplans() as $floorplan)
              <img src="{{ $floorplan->getUrl('thumbStockSlider') }}" alt="{{ $floorplan->name }}">
            @endforeach
          @else
            @foreach($stockItem->floorplans() as $floorplan)
              <img src="{{ $floorplan->getUrl('stockSliderNav') }}" alt="{{ $floorplan->name }}">
            @endforeach
          @endif
        </div>
      </div>
    @endif
    </div>
    <div class="control control-next" tabindex="-1">
      <i class="fas fa-chevron-right" aria-label="hidden"></i>
    </div>
  </div>
</div>

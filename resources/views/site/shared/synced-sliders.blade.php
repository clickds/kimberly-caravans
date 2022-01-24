<div class="synced-slider">
  <div class="main-slider">
    @foreach ($images as $image)
      <div>
        <img src="{{ $image->getFullUrl() }}" alt="{{ $image->name }}">
      </div>
    @endforeach
  </div>
  <div class="flex items-stretch mt-2">
    <div class="control-previous px-1 flex flex-col justify-center bg-gallery" tabindex="-1">
      <i class="fas fa-chevron-left" aria-label="hidden"></i>
    </div>
    <div class="nav-slider">
      @foreach ($images as $image)
        <div>
          <img src="{{ $image->getFullUrl() }}" alt="{{ $image->name }}">
        </div>
      @endforeach
    </div>
    <div class="control-next px-1 flex flex-col justify-center bg-gallery" tabindex="-1">
      <i class="fas fa-chevron-right" aria-label="hidden"></i>
    </div>
  </div>
</div>

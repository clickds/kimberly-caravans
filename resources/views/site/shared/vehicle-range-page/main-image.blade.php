<div class="vehicle-range-main-image">
  <div class="relative detail-container">
    @if ($media)
      <div class="image-container image-object-cover image-object-center">
        {{ $media('show') }}
      </div>
    @endif
    <div class="text-container">
      <div class="content-box  {{ $range->getBannerContentBoxCssClasses() }}">
        <h2 class="{{ $range->getBannerTitleCssClasses() }}">{{ $rangeName }}</h2>
      </div>
    </div>
  </div>
</div>
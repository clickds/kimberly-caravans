<article class="vehicle-range">
  <div class="relative detail-container">
    @if ($media = $motorhomeRange->getFirstMedia('mainImage'))
      <div class="image-container image-object-cover image-object-center">
        {{ $media('show') }}
      </div>
    @endif
    <div class="text-container">
      <div class="content-box {{ $motorhomeRange->getBannerContentBoxCssClasses() }}">
        <h2 class="{{ $motorhomeRange->getBannerTextCssClasses() }}">{{ $motorhomeRange->name }}</h2>
        <div class="{{ $motorhomeRange->getBannerTextCssClasses() }}">
          @if ($motorhomeRange->numberOfModels() > 0)
            <div class="text-2xl mb-5 font-bold">
              {{ $motorhomeRange->numberOfModels() }} models with prices starting from {!! $motorhomeRange->lowestPrice($site) !!}
            </div>
          @endif
          <div class="mb-5">
            {{ $motorhomeRange->overview }}
          </div>
        </div>
        @if($pagePresenter = $motorhomeRange->page($site))
          <a href="{{ $pagePresenter->link() }}" class="button uppercase {{ $motorhomeRange->getBannerButtonCssClasses() }}">
            Explore the range
          </a>
        @endif
      </div>
    </div>
  </div>
</article>
<article class="vehicle-range">
  <div class="relative detail-container">
    @if ($media = $caravanRange->getFirstMedia('mainImage'))
      <div class="image-container image-object-cover image-object-center">
        {{ $media('show') }}
      </div>
    @endif
    <div class="text-container">
      <div class="content-box {{ $caravanRange->getBannerContentBoxCssClasses() }}">
        <h2 class="{{ $caravanRange->getBannerTextCssClasses() }}">{{ $caravanRange->name }}</h2>
        <div class="{{ $caravanRange->getBannerTextCssClasses() }}">
          @if ($caravanRange->numberOfModels() > 0)
            <div class="text-2xl mb-5 font-bold">
              {{ $caravanRange->numberOfModels() }} models with prices starting from {!! $caravanRange->lowestPrice($site) !!}
            </div>
          @endif
          <div class="mb-5">
            {{ $caravanRange->overview }}
          </div>
        </div>
        @if($pagePresenter = $caravanRange->page($site))
          <a href="{{ $pagePresenter->link() }}" class="button uppercase {{ $caravanRange->getBannerButtonCssClasses() }}">
            Explore the range
          </a>
        @endif
      </div>
    </div>
  </div>
</article>
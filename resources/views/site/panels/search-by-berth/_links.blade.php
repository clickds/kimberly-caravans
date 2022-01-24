<div class="bg-white p-2">
  <div class="border-2 border-gallery p-2">
    <div class="text-shiraz mx-auto w-1/3 h-12 flex justify-center">
      @include($iconPartial)
    </div>

    <div class="font-heading font-bold text-regal-blue text-xl text-center capitalize">
      {{ $title }}
      <span class="block text-base">
        by berth
      </span>
    </div>

    <div class="-mx-2 flex flex-wrap">
      @foreach ($berthOptions as $berthOption)
        <div class="px-2 w-1/2">
          <a class="block" href="{{ $page->link($panel->berthOptionQueryParameters($berthOption)) }}">
            <div class="search-icon">
              <div class="column">
                @include('site.shared.svg-icons.berth')
              </div>
              <div class="column">
                {{ $berthOption['shortDisplayName'] }}
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</div>
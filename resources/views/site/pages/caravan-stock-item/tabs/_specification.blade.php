<div class="w-full container mx-auto lg:px-12">
  <div class="w-full my-5 p-4 bg-alabaster flex flex-wrap">
    <div class="w-full border-silver-gray lg:w-1/2 lg:pr-5 lg:border-r">
      <h4 class="mb-4 font-bold capitalize text-endeavour text-lg flex items-center">
        <div>
          Key facts
        </div>
        <div class="h-6 w-10 text-shiraz ml-2">
          @include('site.shared.svg-icons.caravan')
        </div>
      </h4>
      <div class="mb-2 flex flex-wrap text-gray-700">
        @if ($stockItem->berthString())
          <div class="mb-2 w-1/2 font-bold text-capitalize">
            Berths:
          </div>
          <div class="w-1/2">
            {{ $stockItem->berthString() }}
          </div>
        @endif

        @if ($stockItem->axles)
          <div class="mb-2 w-1/2 font-bold text-capitalize">
            Axles:
          </div>
          <div class="mb-2 w-1/2">
            {{ $stockItem->axles }}
          </div>
        @endif
      </div>
    </div>

    <div class="w-full lg:w-1/2 lg:pl-5">
      <h4 class="mb-4 font-bold capitalize text-endeavour text-lg flex items-center">
        <div>
          Weight & Dimensions
        </div>
        <div class="h-6 w-10 text-shiraz ml-2">
          @include('site.shared.svg-icons.weight-and-dimensions')
        </div>
      </h4>

      <div class="flex flex-wrap text-gray-700">
        @if ($stockItem->layoutName())
          <div class="mb-2 w-1/2 font-bold text-capitalize">
            Layout:
          </div>
          <div class="w-1/2">
            {{ $stockItem->layoutName() }}
          </div>
        @endif

        <div class="mb-2 w-1/2 font-bold text-capitalize">
          Width:
        </div>
        <div class="mb-2 w-1/2">
          {{ $stockItem->formattedWidth() }}
        </div>

        <div class="mb-2 w-1/2 font-bold text-capitalize">
          Length:
        </div>
        <div class="w-1/2">
          {{ $stockItem->formattedLength() }}
        </div>

        <div class="mb-2 w-1/2 font-bold text-capitalize">
          Height:
        </div>
        <div class="w-1/2">
          {{ $stockItem->formattedHeight() }}
        </div>

        <div class="mb-2 w-1/2 font-bold text-capitalize">
          MRO:
        </div>
        <div class="w-1/2">
          {{ $stockItem->formattedMro() }}
        </div>

        <div class="mb-2 w-1/2 font-bold text-capitalize">
          MTPLM:
        </div>
        <div class="w-1/2">
          {{ $stockItem->formattedMtplm() }}
        </div>

        <div class="mb-2 w-1/2 font-bold text-capitalize">
          Est. Payload*:
        </div>
        <div class="w-1/2">
          {{ $stockItem->formattedPayload() }}
        </div>
      </div>
    </div>
    <p class="mt-4">
      *Payload figures are taken from the original manufacturer's specification at the time of production and may vary with used goods.
      The quoted figure should only be used as a guideline. Actual payload may be obtained by request.
    </p>
  </div>

  <div class="w-full">
    <h4 class="mb-4 font-bold capitalize text-endeavour text-lg">
      Features
    </h4>
    <div class="-mx-4 flex flex-wrap">
      @if ($stockItem->features($site)->isNotEmpty())
        @if ($stockItem->hasFeedSource())
          @foreach ($stockItem->features($site)->chunk(3) as $chunk)
            <div class="px-4 w-full md:w-1/3 border-silver-gray{{ $loop->last ? '' : ' md:border-r' }}">
              @foreach($chunk as $feature)
                <div class="mb-2">
                  <div class="inline-block text-shiraz w-4 h-4">
                    @include('site.shared.svg-icons.features-tick')
                  </div>
                  {{ $feature->name }}
                </div>
              @endforeach
            </div>
          @endforeach
        @else
          @foreach ($stockItem->features($site) as $feature)
            <div class="px-4 w-full stock-item-feature-wysiwyg">
              {!! $feature->content !!}
            </div>
          @endforeach
        @endif
      @endif
    </div>
  </div>
</div>
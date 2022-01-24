<div class="grid {{ 'motorhome' === $vehicleType ? 'grid-cols-4' : 'grid-cols-3' }} gap-5">
  <div class="w-full">
    <div class="font-bold capitalize text-endeavour flex items-center">
      <div>
        Key facts
      </div>
      <div class="h-3 w-5 text-shiraz ml-2">
        @if('caravan' === $vehicleType)
          @include('site.shared.svg-icons.caravan')
        @endif
        @if('motorhome' === $vehicleType)
          @include('site.shared.svg-icons.motorhome')
        @endif
      </div>
    </div>
    <div class="flex flex-wrap text-gray-700">
      @if ($stockItem->berthString())
        <div class="w-1/2 font-bold text-capitalize">
          Berths:
        </div>
        <div class="w-1/2">
          {{ $stockItem->berthString() }}
        </div>
      @endif

      @if('motorhome' === $vehicleType)
        @if($stockItem->seats->isNotEmpty())
          <div class="w-1/2 font-bold text-capitalize">
            Seat belts:
          </div>
          <div class="w-1/2">
            {{ $stockItem->seats->map(function ($item) { return $item->number; })->implode('/') }}
          </div>
        @endif
      @endif

      @if ($stockItem->axles)
        <div class="w-1/2 font-bold text-capitalize">
          Axles:
        </div>
        <div class="w-1/2">
          {{ $stockItem->axles }}
        </div>
      @endif

      @if ('motorhome' === $vehicleType)
        @if ($stockItem->formattedRegistrationDate())
          <div class="w-1/2 font-bold text-capitalize">
            Date of Registration:
          </div>
          <div class="w-1/2">
            {{ $stockItem->formattedRegistrationDate() }}
          </div>
        @endif

        @if ($stockItem->registration_number)
          <div class="w-1/2 font-bold text-capitalize">
            Registration:
          </div>
          <div class="w-1/2">
            {{ $stockItem->registration_number }}
          </div>
        @endif
      @endif
    </div>
  </div>

  @if('motorhome' === $vehicleType)
    <div class="w-full">
      <div class="font-bold capitalize text-endeavour flex items-center">
        <div>
          Engine & Chassis
        </div>
        <div class="h-3 w-5 text-shiraz ml-2">
          @include('site.shared.svg-icons.engine-and-chassis')
        </div>
      </div>

      <div class="flex flex-wrap text-gray-700">
        @if ($stockItem->mileage)
          <div class="w-1/2 font-bold text-capitalize">
            Mileage:
          </div>
          <div class="w-1/2">
            {{ $stockItem->mileage }}
          </div>
        @endif

        @if ($stockItem->conversion)
          <div class="w-1/2 font-bold text-capitalize">
            Conversion:
          </div>
          <div class="w-1/2">
            {{ $stockItem->conversion }}
          </div>
        @endif

        @if ($stockItem->chassis_manufacturer)
          <div class="w-1/2 font-bold text-capitalize">
            Chassis:
          </div>
          <div class="w-1/2">
            {{ $stockItem->chassis_manufacturer }}
          </div>
        @endif

        @if ($stockItem->fuel)
          <div class="w-1/2 font-bold text-capitalize">
            Fuel
          </div>
          <div class="w-1/2">
            {{ $stockItem->fuel }}
          </div>
        @endif

        @if ($stockItem->transmission)
          <div class="w-1/2 font-bold text-capitalize">
            Transmission:
          </div>
          <div class="w-1/2">
            {{ $stockItem->transmission }}
          </div>
        @endif

        @if ($stockItem->engine_size)
          <div class="w-1/2 font-bold text-capitalize">
            Engine Size:
          </div>
          <div class="w-1/2">
            {{ $stockItem->engine_size }}
          </div>
        @endif
      </div>
    </div>
  @endif

  <div class="w-full">
    <div class="font-bold capitalize text-endeavour flex items-center">
      <div>
        Weight & Dimensions
      </div>
      <div class="h-3 w-5 text-shiraz ml-2">
        @include('site.shared.svg-icons.weight-and-dimensions')
      </div>
    </div>

    <div class="flex flex-wrap text-gray-700">
      @if ($stockItem->layoutName())
        <div class="w-1/2 font-bold text-capitalize">
          Layout:
        </div>
        <div class="w-1/2">
          {{ $stockItem->layoutName() }}
        </div>
      @endif

      <div class="w-1/2 font-bold text-capitalize">
        Width:
      </div>
      <div class="w-1/2">
        {{ $stockItem->formattedWidth() }}
      </div>

      <div class="w-1/2 font-bold text-capitalize">
        Length:
      </div>
      <div class="w-1/2">
        {{ $stockItem->formattedLength() }}
      </div>

      <div class="w-1/2 font-bold text-capitalize">
        Height:
      </div>
      <div class="w-1/2">
        {{ $stockItem->formattedHeight() }}
      </div>

      <div class="w-1/2 font-bold text-capitalize">
        MRO:
      </div>
      <div class="w-1/2">
        {{ $stockItem->formattedMro() }}
      </div>

      <div class="w-1/2 font-bold text-capitalize">
        MTPLM:
      </div>
      <div class="w-1/2">
        {{ $stockItem->formattedMtplm() }}
      </div>

      <div class="w-1/2 font-bold text-capitalize">
        Est. Payload*:
      </div>
      <div class="w-1/2">
        {{ $stockItem->formattedPayload() }}
      </div>
    </div>
  </div>

  <div class="w-full">
    <div class="font-bold capitalize text-endeavour">
      Features
    </div>
    <div class="flex flex-wrap text-gray-700">
      @if ($stockItem->features($site)->isNotEmpty())
        @if ($stockItem->hasFeedSource())
          @foreach ($stockItem->features($site)->chunk(3) as $chunk)
            <div class="{{ 'motorhome' === $vehicleType ? 'w-full' : 'w-1/2' }}">
              @foreach($chunk as $feature)
                <div>
                  <div class="inline-block text-shiraz w-2 h-2">
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
  <p class="{{ 'motorhome' === $vehicleType ? 'col-span-4' : 'col-span-3' }}">
    *Payload figures are taken from the original manufacturer's specification at the time of production and may vary with used goods.
    The quoted figure should only be used as a guideline. Actual payload may be obtained by request.
  </p>
</div>
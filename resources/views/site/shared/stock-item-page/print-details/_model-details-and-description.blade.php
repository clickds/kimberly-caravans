<div>
  <div class="flex flex-row w-full">
    <div class="w-3/4">
      @if ($stockItem->stockReference())
        <p>{{ $stockItem->stockReference() }}</p>
      @endif
      <h1 class="text-xl font-semibold">
        {{ $stockItem->title() }}
      </h1>

      <div class="flex -mx-2">
        @foreach($stockItem->mainDetails() as $detail)
          <div class="px-2 border-gallery {{ $loop->last ? '' : ' border-r' }}">
            {{ $detail }}
          </div>
        @endforeach
      </div>

      @if($stockItem->attention_grabber)
        <div class="py-2">
          {{ $stockItem->attention_grabber }}
        </div>
      @endif

      <hr class="border-gallery my-2" />

      <div class="flex flex-col">
        <div class="-mx-2 px-2 flex-grow text-xl">
          @if ($stockItem->isNew())
            @if ($stockItem->isOnSale())
              <div><span class="text-silver-gray">Now only</span> {!! $stockItem->formattedPrice($site) !!}</div>
              <div class="text-silver-gray"><span>Was new</span> <span class="line-through">{!! $stockItem->formattedRecommendedPrice($site) !!}</span></div>
              <div class="text-endeavour"><span>Save</span> {!! $stockItem->formattedPriceReduction($site) !!}</div>
            @else
              <div><span class="text-dove-gray">OTR from</span> {!! $stockItem->formattedPrice($site) !!}</div>
            @endif
          @else
            @if ($stockItem->isOnSale())
              <div><span class="text-silver-gray">Now only</span> {!! $stockItem->formattedPrice($site) !!}</div>
              <div class="text-silver-gray"><span>Was</span> <span class="line-through">{!! $stockItem->formattedRecommendedPrice($site) !!}</span></div>
              <div class="text-monarch"><span>Save</span> {!! $stockItem->formattedPriceReduction($site) !!}</div>
            @else
              <div>{!! $stockItem->formattedPrice($site) !!}</div>
            @endif
          @endif
        </div>
        <div>{{ $stockItem->otrInformation() }}</div>
      </div>

      <hr class="border-gallery my-2" />
    </div>
    <div class="w-1/4">
      <img src="/images/logo-grey-text.svg" class="w-full">
    </div>
  </div>
  <div class="w-full">
    <div class="font-bold">Description</div>
    <div>{{ $stockItem->description }}</div>
  </div>
</div>

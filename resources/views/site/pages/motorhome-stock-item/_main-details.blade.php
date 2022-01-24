@if ($stockItem->stockReference())
  <p>
    {{ $stockItem->stockReference() }}
  </p>
@endif
<h1 class="text-h2 font-semibold">
  {{ $stockItem->title() }}
</h1>

<div class="flex -mx-2 mb-2">
  @foreach($stockItem->mainDetails() as $detail)
    <div class="px-2 border-gallery {{ $loop->last ? '' : ' border-r' }}">
      {{ $detail }}
    </div>
  @endforeach
</div>

<div class="py-2">
  {{ $stockItem->attention_grabber }}
</div>

<div class="grid grid-cols-2">
  @foreach($stockItem->floorplans() as $floorplan)
    {{ $floorplan }}
  @endforeach
</div>

<hr class="border-gallery my-2" />

<div class="-mx-2 flex items-center">
  <div class="px-2 flex-grow text-3xl">
    @if ($stockItem->isNew())
      @if ($stockItem->isOnSale())
        <div><span class="text-silver-gray text-lg">Now OTR from</span> {!! $stockItem->formattedPrice($site) !!}</div>
        <div class="text-silver-gray"><span class="text-lg">Was new</span> <span class="line-through">{!! $stockItem->formattedRecommendedPrice($site) !!}</span></div>
        <div class="text-monarch"><span class="text-lg">Save</span> {!! $stockItem->formattedPriceReduction($site) !!}</div>
      @else
        <div><span class="text-dove-gray text-lg">OTR from</span> {!! $stockItem->formattedRecommendedPrice($site) !!}</div>
      @endif
    @else
      @if ($stockItem->isOnSale())
        <div><span class="text-silver-gray text-lg">Now only</span> {!! $stockItem->formattedPrice($site) !!}</div>
        <div class="text-silver-gray"><span class="text-lg">Was</span> <span class="line-through">{!! $stockItem->formattedRecommendedPrice($site) !!}</span></div>
        <div class="text-monarch"><span class="text-lg">Save</span> {!! $stockItem->formattedPriceReduction($site) !!}</div>
      @else
        <div>{!! $stockItem->formattedRecommendedPrice($site) !!}</div>
      @endif
    @endif
    <div class="text-sm">{{ $stockItem->otrInformation() }}</div>
  </div>
  <div class="px-2 w-32">
    @include('site.shared.calculate-monthly-payment-button')
  </div>
</div>

<hr class="border-gallery my-2" />

@include('site.shared.stock-form-modal', [
  'dealers' => $dealers,
  'itemDetails' => $stockItem->stockFormDetails(),
  'size' => 'small',
])
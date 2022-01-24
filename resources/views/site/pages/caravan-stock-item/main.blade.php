@php
  $stockItem = $pageFacade->getStockItem();
@endphp

<div class="print:hidden">
  <div class="container mx-auto py-2 px-standard">
    @include('site.shared.stock-item-page.top-bar', [
      'comparisonPageExists' => $pageFacade->comparisonPageExists(),
      'stockItem' => $stockItem,
    ])

    <div class="py-2 grid grid-cols-1 md:grid-cols-2 gap-4">
      @include('site.shared.stock-item-page.synced-sliders', [
        'stockItem' => $pageFacade->getStockItem(),
      ])
      <div>
        @include('site.pages.caravan-stock-item._main-details', [
          'dealers' => $pageFacade->getDealers(),
          'stockItem' => $pageFacade->getStockItem(),
          'site' => $pageFacade->getSite(),
        ])
      </div>
    </div>
  </div>

  @include('site.shared.stock-item-page.special-offer-slider', [
    'specialOfferPages' => $pageFacade->getSpecialOfferPages(),
  ])

  @include('site.pages.caravan-stock-item._tabs', [
    'stockItem' => $stockItem,
    'site' => $pageFacade->getSite(),
    'warrantyPage' => $pageFacade->getWarrantyPage(),
    'manufacturersWarrantyPage' => $pageFacade->getManufacturersWarrantyPage(),
  ])

  @if ($stockItem->price)
  <similar-stock-items-slider url="{{ route('api.caravan-stock-items.search') }}" :berths="{{ $stockItem->berths->map->number }}"
    layout="{{ $stockItem->layoutName() }}" :price="{{ $stockItem->price }}"
    :stock-item-id="{{ $stockItem->id }}"></similar-stock-items-slider>
  @endif
</div>

@include('site.shared.stock-item-page.print-details.main', [
  'stockItem' => $stockItem,
  'site' => $pageFacade->getSite(),
  'vehicleType' => 'caravan',
])
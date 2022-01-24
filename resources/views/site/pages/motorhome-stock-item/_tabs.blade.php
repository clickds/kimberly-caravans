<section class="component tabs">
  <tabs :tabs='{{ json_encode($stockItem->showPageTabs()) }}'>
    <template #specification>
      @include('site.pages.motorhome-stock-item.tabs._specification', [
        'stockItem' => $stockItem,
        'site' => $site,
      ])
    </template>
    <template #warranty>
      @include('site.pages.motorhome-stock-item.tabs._warranty', [
        'stockItem' => $stockItem,
        'site' => $site,
        'warrantyPage' => $warrantyPage,
        'manufacturersWarrantyPage' => $manufacturersWarrantyPage,
      ])
    </template>
    <template #part-exchange>
      @include('site.pages.motorhome-stock-item.tabs._part-exchange', [
        'form' => $pageFacade->getPartExchangeForm()
      ])
    </template>
    @if ($stockItem->recommended_price || $stockItem->price)
    <template #finance-calculator>
      @include('site.pages.motorhome-stock-item.tabs._finance-calculator', [
        'site' => $site,
        'stockItem' => $stockItem,
      ])
    </template>
    @endif
  </tabs>
</section>
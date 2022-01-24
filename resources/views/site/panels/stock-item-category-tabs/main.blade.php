<stock-item-category-tabs-panel
  type='{{ $panel->getVehicleType() }}'
  caravan-api-search-url="{{ route('api.caravan-stock-items.search') }}"
  motorhome-api-search-url="{{ route('api.motorhome-stock-items.search') }}"
  new-stock-filter='{{ $panel->newStockFilter() }}'
  used-stock-filter='{{ $panel->usedStockFilter() }}'
  new-arrivals-filter='{{ $panel->newArrivalsFilter() }}'
  @if($page = $panel->getCaravanSearchPage())caravan-search-page-url='{{ $page->link() }}'@endif
  @if($page = $panel->getMotorhomeSearchPage())motorhome-search-page-url='{{ $page->link() }}'@endif
></stock-item-category-tabs-panel>
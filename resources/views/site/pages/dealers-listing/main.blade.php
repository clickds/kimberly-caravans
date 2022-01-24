<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Marquis Locations</h1>
    @if($pageFacade->getSite()->is_default)
    <h3 class="font-medium">
      With {{ $pageFacade->getDealerCount() }} branches across the UK, Marquis are easily accessible from all major roads and motorways.
    </h3>
    @endif
  </div>
</div>

@include('site.pages.dealers-listing._postcode-search')

<div class="bg-gallery">
  <div class="py-8 container mx-auto px-standard space-x-3">
    <a
      href="{{ $pageFacade->getListViewUrl() }}"
      class="text-lg font-bold {{ $pageFacade->listViewSelected() ? 'text-shiraz' : 'text-endeavour underline' }}"
    >
      List View
    </a>
    <a
      href="{{ $pageFacade->getMapViewUrl() }}"
      class="text-lg font-bold {{ $pageFacade->mapViewSelected() ? 'text-shiraz' : 'text-endeavour underline' }}"
    >
      Map View
    </a>
  </div>
  @if($pageFacade->mapViewSelected())
    <div class="pb-10 container mx-auto px-standard">
      <locations-map
        :locations='@json($pageFacade->getDealerMapData())'
      ></locations-map>
    </div>
  @else
    <div class="pb-10 container mx-auto px-standard grid gap-5 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
      @foreach ($pageFacade->getDealers() as $dealer)
        @include('site.shared.dealer-card', ['dealer' => $dealer])
      @endforeach
    </div>
  @endif
</div>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])
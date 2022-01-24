<div class="h-full search-by-berth search-by-berth--{{ $panel->getVehicleType() }}">
  <div class="mt-2 mb-6 md:mb-24 font-heading text-center text-white">
    <span class="block text-2xl">Here to help you find your</span>
    <span class="block text-2xl font-bold">Perfect {{ $panel->getTitleContent() }}</span>
    <span>Choose by Berth</span>
  </div>
  <div class="px-2 flex flex-wrap justify-around">
    @if ($panel->displayMotorhomes() && $panel->getMotorhomeSearchPage())
      <div class="w-full lg:w-1/2 my-2 px-2">
        @include('site.panels.search-by-berth._links', [
          'page' => $panel->getMotorhomeSearchPage(),
          'panel' => $panel,
          'berthOptions' => $panel->getMotorhomeBerthOptions(),
          'title' => 'Motorhomes',
          'iconPartial' => 'site.shared.svg-icons.motorhome',
        ])
      </div>
    @endif

    @if ($panel->displayCaravans() && $panel->getCaravanSearchPage())
      <div class="w-full lg:w-1/2 my-2 px-2">
        @include('site.panels.search-by-berth._links', [
          'page' => $panel->getCaravanSearchPage(),
          'panel' => $panel,
          'berthOptions' => $panel->getCaravanBerthOptions(),
          'title' => 'Caravans',
          'iconPartial' => 'site.shared.svg-icons.caravan',
        ])
      </div>
    @endif
  </div>
</div>
<div class="manufacturer-slider-container">
  @if($panel->motorhomeManufacturerPages()->isNotEmpty())
    <div class="container mx-auto mb-4">
      <h2 class="text-center text-web-orange mb-2">Motorhome brands</h2>

      <div class="slider-container px-standard">
        <div class="manufacturer-slider relative z-20">
          @foreach ($panel->motorhomeManufacturerPages() as $page)
            @include('site.panels.manufacturer-slider._manufacturer-page', [
              'page' => $page,
            ])
          @endforeach
        </div>

        <ul class="controls m-0 p-0 absolute left-0 list-none w-full flex items-center justify-between px-standard container text-regal-blue" aria-label="Carousel Navigation" tabindex="0">
          <li class="prev cursor-pointer" data-controls="prev" aria-controls="customize" tabindex="-1">
            <i class="fas fa-angle-left fa-2x"></i>
          </li>
          <li class="next cursor-pointer" data-controls="next" aria-controls="customize" tabindex="-1">
            <i class="fas fa-angle-right fa-2x"></i>
          </li>
        </ul>
      </div>
    </div>
  @endif

  @if($panel->caravanManufacturerPages()->isNotEmpty())
    <div class="container mx-auto">
      <h2 class="text-center text-web-orange mb-2">Caravan brands</h2>

      <div class="slider-container px-standard">
        <ul class="controls" aria-label="Carousel Navigation" tabindex="0">
          <li class="prev" data-controls="prev" aria-controls="customize" tabindex="-1">
            <i class="fas fa-angle-left fa-2x"></i>
          </li>
          <li class="next" data-controls="next" aria-controls="customize" tabindex="-1">
            <i class="fas fa-angle-right fa-2x"></i>
          </li>
        </ul>
        <div class="manufacturer-slider">
          @foreach ($panel->caravanManufacturerPages() as $page)
            @include('site.panels.manufacturer-slider._manufacturer-page', [
              'page' => $page,
            ])
          @endforeach
        </div>
      </div>
    </div>
  @endif
</div>
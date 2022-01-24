<div class="mobile-secondary-menu md:hidden">
  @if ($pageFacade->getSite()->display_exclusive_manufacturers_separately)
    <div class="accordion-container slide-toggle">
      <div class="flex items-stretch">
        <div class="title">
          @if ($newMotorhomesNavigationItem = $mainNavigation->newMotorhomesPage())
            <a href="{{ $newMotorhomesNavigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}">
              Motorhomes
            </a>
          @else
            Motorhomes
          @endif
        </div>
        <div class="toggle-container">
          <button class="w-full flex justify-center items-center" data-toggle="open">
            <i class="fas fa-2x fa-chevron-down"></i>
          </button>
          <button class="w-full hidden" data-toggle="close">
            <i class="fas fa-2x fa-chevron-up"></i>
          </button>
        </div>
      </div>
      <div class="vehicle-range-links toggle-content" data-toggle="content">
        @if($exclusiveMotorhomePages->isNotEmpty())
          @include('layouts.header.navigation._mobile-manufacturer-page-links', [
            'pages' => $exclusiveMotorhomePages,
            'title' => 'Exclusive',
          ])
        @endif

        @if($otherMotorhomeManufacturerPages->isNotEmpty())
          @include('layouts.header.navigation._mobile-manufacturer-page-links', [
            'pages' => $otherMotorhomeManufacturerPages,
            'title' => 'Other Manufacturers',
          ])
        @endif
      </div>
    </div>
  @elseif($allMotorhomeManufacturerPages->isNotEmpty())
    <div class="accordion-container slide-toggle">
      <div class="flex items-stretch">
        <div class="title">
          @if ($newMotorhomesNavigationItem = $mainNavigation->newMotorhomesPage())
            <a href="{{ $newMotorhomesNavigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}">
              Motorhomes
            </a>
          @else
            Motorhomes
          @endif
        </div>
        <div class="toggle-container">
          <button class="w-full flex justify-center items-center" data-toggle="open">
            <i class="fas fa-2x fa-chevron-down"></i>
          </button>
          <button class="w-full hidden" data-toggle="close">
            <i class="fas fa-2x fa-chevron-up"></i>
          </button>
        </div>
      </div>
      <div class="vehicle-range-links toggle-content" data-toggle="content">
        @include('layouts.header.navigation._mobile-manufacturer-page-links', [
          'pages' => $allMotorhomeManufacturerPages,
          'title' => 'All Manufacturers',
        ])
      </div>
    </div>
  @endif

  @if ($pageFacade->getSite()->display_exclusive_manufacturers_separately)
    <div class="accordion-container slide-toggle">
      <div class="flex items-stretch">
        <div class="title">
          @if ($newCaravansNavigationItem = $mainNavigation->newCaravansPage())
            <a href="{{ $newCaravansNavigationItem->linkUrl() }}">
              Caravans
            </a>
          @else
            Caravans
          @endif
        </div>
        <div class="toggle-container">
          <button class="w-full flex justify-center items-center" data-toggle="open">
            <i class="fas fa-2x fa-chevron-down"></i>
          </button>
          <button class="w-full hidden" data-toggle="close">
            <i class="fas fa-2x fa-chevron-up"></i>
          </button>
        </div>
      </div>
      <div class="vehicle-range-links toggle-content" data-toggle="content">
        @if($exclusiveCaravanPages->isNotEmpty())
          @include('layouts.header.navigation._mobile-manufacturer-page-links', [
            'pages' => $exclusiveCaravanPages,
            'title' => 'Exclusive',
          ])
        @endif

        @if($otherCaravanManufacturerPages->isNotEmpty())
          @include('layouts.header.navigation._mobile-manufacturer-page-links', [
            'pages' => $otherCaravanManufacturerPages,
            'title' => 'Other Manufacturers',
          ])
        @endif
      </div>
    </div>
  @elseif($allCaravanManufacturerPages->isNotEmpty())
    <div class="accordion-container slide-toggle">
      <div class="flex items-stretch">
        <div class="title">
          @if ($newCaravansNavigationItem = $mainNavigation->newCaravansPage())
            <a href="{{ $newCaravansNavigationItem->linkUrl() }}">
              Caravans
            </a>
          @else
            Caravans
          @endif
        </div>
        <div class="toggle-container">
          <button class="w-full flex justify-center items-center" data-toggle="open">
            <i class="fas fa-2x fa-chevron-down"></i>
          </button>
          <button class="w-full hidden" data-toggle="close">
            <i class="fas fa-2x fa-chevron-up"></i>
          </button>
        </div>
      </div>
      <div class="vehicle-range-links toggle-content" data-toggle="content">
        @include('layouts.header.navigation._mobile-manufacturer-page-links', [
          'pages' => $allCaravanManufacturerPages,
          'title' => 'All Manufacturers',
        ])
      </div>
    </div>
  @endif

  @if($fullMenuNavigation->navigationItems->isNotEmpty())
    @foreach ($fullMenuNavigation->navigationItems as $navigationItem)
      <div class="accordion-container slide-toggle">
        <div class="flex items-stretch">
          <div class="title">
            <a href="{{ $navigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}">
              {{ $navigationItem->name }}
            </a>
          </div>
          <div class="toggle-container">
            <button class="w-full flex justify-center items-center" data-toggle="open">
              <i class="fas fa-2x fa-chevron-down"></i>
            </button>
            <button class="w-full hidden" data-toggle="close">
              <i class="fas fa-2x fa-chevron-up"></i>
            </button>
          </div>
        </div>
        <div class="child-navigation-links toggle-content" data-toggle="content">
          @if ($navigationItem->children->isNotEmpty())
            <ul class="child-navigation">
              @foreach ($navigationItem->children as $navigationItem)
                <li>
                  <a href="{{ $navigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}">
                    {{ $navigationItem->name }}
                  </a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    @endforeach
  @endif
</div>

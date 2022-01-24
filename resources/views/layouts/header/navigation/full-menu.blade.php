<nav role="navigation" aria-label="Full menu" class="full-menu invisible opacity-0">
  <div class="container mx-auto px-0 md:px-4">
    <div class="flex flex-wrap justify-between">
      <div class="w-full md:w-auto mb-2 md:mb-4 text-center md:text-left">
        <a href="/" class="inline-block">
          <img src="/images/logo-white-text.svg" class="w-40">
        </a>
      </div>
      <div class="w-full px-4 md:px-0 md:w-3/4  flex items-center">
        @if($searchPageUrl)
          <form action="{{ $searchPageUrl }}" class="flex-grow flex-shrink flex mb-2 md:mb-4" method="get">
            <input class="w-0 appearance-none bg-transparent border-b border-white flex-grow flex-shrink text-white placeholder-white py-1 px-2 leading-tight focus:outline-none text-xl" type="text" name="query" placeholder="Search" aria-label="search" required>
            <button class="md:w-auto flex-shrink-0 px-3 py-3" type="submit">
              <div class="w-8 h-8 cursor-pointer text-white toggle-search">
                @include('site.shared.svg-icons.search')
              </div>
            </button>
          </form>
        @endif
        <div class="mb-2 md:mb-4">
          <button aria-label="toggle full menu" class="full-menu-button cursor-pointer text-white py-2">
            <i class="far fa-times-circle fa-2x"></i>
          </button>
        </div>
      </div>
    </div>
    @if ($mainNavigation->navigationItems->isNotEmpty())
      <div class="w-full icon-navigation flex flex-wrap md:-mx-2">
        @foreach ($mainNavigation->navigationItems as $navigationItem)
          <div class="{{ $navigationItem->fullMenuMainItemContainerCssClasses($loop->index) }}">
            <a href="{{ $navigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}"
              class="navigation-item">
              @if ($navigationItem->iconPartialPath())
                <div class="icon-container bg-web-orange text-shiraz md:text-white w-1/5 h-full p-2 flex items-center">
                  @include($navigationItem->iconPartialPath())
                </div>
                <div class="w-4/5 p-4">
                  {{ $navigationItem->name }}
                </div>
              @else
                <div class="p-4">
                  {{ $navigationItem->name }}
                </div>
              @endif
            </a>
          </div>
        @endforeach
      </div>
    @endif

    @include('layouts.header.navigation._mobile-secondary-menu', [
      'mainNavigation' => $mainNavigation,
      'fullMenuNavigation' => $fullMenuNavigation,
      'exclusiveMotorhomePages' => $exclusiveMotorhomePages,
      'otherMotorhomeManufacturerPages' => $otherMotorhomeManufacturerPages,
      'exclusiveCaravanPages' => $exclusiveCaravanPages,
      'otherCaravanManufacturerPages' => $otherCaravanManufacturerPages,
    ])

    <div class="hidden secondary-columns-container md:flex flex-wrap -mx-4 mt-4">
      @if ($pageFacade->getSite()->display_exclusive_manufacturers_separately)
        @if ($exclusiveMotorhomePages->isNotEmpty() || $otherMotorhomeManufacturerPages->isNotEmpty())
          <div class="column-container w-full md:w-1/3 lg:w-1/5 px-4">
            <h3 class="title">
              @if ($newMotorhomesNavigationItem = $mainNavigation->newMotorhomesPage())
                <a href="{{ $newMotorhomesNavigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}">
                  Motorhomes
                </a>
              @else
                Motorhomes
              @endif
            </h3>

            @include('layouts.header.navigation._manufacturer-page-links', [
              'pages' => $exclusiveMotorhomePages,
              'title' => 'Exclusive',
            ])

            @include('layouts.header.navigation._manufacturer-page-links', [
              'pages' => $otherMotorhomeManufacturerPages,
              'title' => 'Other Manufacturers',
            ])
          </div>
        @endif
      @elseif($allMotorhomeManufacturerPages->isNotEmpty())
        <div class="column-container w-full md:w-1/3 lg:w-1/5 px-4">
          <h3 class="title">
            @if ($newMotorhomesNavigationItem = $mainNavigation->newMotorhomesPage())
              <a href="{{ $newMotorhomesNavigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}">
                Motorhomes
              </a>
            @else
              Motorhomes
            @endif
          </h3>

          @include('layouts.header.navigation._manufacturer-page-links', [
            'pages' => $allMotorhomeManufacturerPages,
            'title' => 'All Manufacturers',
          ])
        </div>
      @endif

      @if ($pageFacade->getSite()->display_exclusive_manufacturers_separately)
        @if ($exclusiveCaravanPages->isNotEmpty() || $otherCaravanManufacturerPages->isNotEmpty())
          <div class="column-container w-full md:w-1/3 lg:w-1/5 px-4">
            <h3 class="title">
              @if ($newCaravansNavigationItem = $mainNavigation->newCaravansPage())
                <a href="{{ $newCaravansNavigationItem->linkUrl() }}">
                  Caravans
                </a>
              @else
                Caravans
              @endif
            </h3>

            @include('layouts.header.navigation._manufacturer-page-links', [
              'pages' => $exclusiveCaravanPages,
              'title' => 'Exclusive',
            ])

            @include('layouts.header.navigation._manufacturer-page-links', [
              'pages' => $otherCaravanManufacturerPages,
              'title' => 'Other Manufacturers',
            ])
          </div>
        @endif
      @elseif($allCaravanManufacturerPages->isNotEmpty())
        <div class="column-container w-full md:w-1/3 lg:w-1/5 px-4">
          <h3 class="title">
            @if ($newCaravansNavigationItem = $mainNavigation->newCaravansPage())
              <a href="{{ $newCaravansNavigationItem->linkUrl() }}">
                Caravans
              </a>
            @else
              Caravans
            @endif
          </h3>

          @include('layouts.header.navigation._manufacturer-page-links', [
            'pages' => $allCaravanManufacturerPages,
            'title' => 'All Manufacturers',
          ])
        </div>
      @endif

      @if($fullMenuNavigation->navigationItems->isNotEmpty())
        @foreach ($fullMenuNavigation->navigationItems as $navigationItem)
          <div class="column-container w-full md:w-1/3 lg:w-1/5 px-4">
            <h3 class="title">
              <a href="{{ $navigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}">
                {{ $navigationItem->name }}
              </a>
            </h3>

            @if ($navigationItem->children->isNotEmpty())
              <ul class="nav-column">
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
        @endforeach
      @endif
    </div>
  </div>
</nav>
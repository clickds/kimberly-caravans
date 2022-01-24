<section class="main-header relative hidden lg:block print:hidden">
  <div class="main-header__top font-heading container mx-auto font-bold text-gray flex items-center py-2 lg:py-5">
    <div class="flex justify-center w-5/12 md:w-4/12 lg:justify-start">
      <a href="/" class="w-40 lg:w-64">
        <img src="/images/logo-grey-text.svg" class="w-full">
      </a>
    </div>

    <div class="text-dove-gray w-7/12 flex flex-col justify-center text-xs sm:text-lg md:text-xl">
      @if ($currentSite->show_opening_times_and_telephone_number)
        @if ($currentSite->phone_number)
          <div class="flex items-center mb-3 font-extrabold uppercase">
            <div class="h-6 mr-3 text-shiraz">
              @include('site.shared.svg-icons.phone')
            </div>
            <a href="tel:{{ $currentSite->phone_number }}">Call: {{ $currentSite->phone_number }}</a>
          </div>
        @endif

        <div class="flex items-center mb-3 font-extrabold uppercase">
          <div class="h-6 mr-3 text-shiraz">
            @include('site.shared.svg-icons.clock')
          </div>
          <span>
            @if($currentOpeningTime->isOpen($currentSite->timezone))
              Open now: {{ $currentOpeningTime->openingHours() }}
            @else
              Closed
            @endif
          </span>
        </div>
      @endif

      @if($searchPageUrl)
        <div class="toggle-search cursor-pointer flex items-center mb-3 font-extrabold uppercase">
          <div class="h-6 mr-3 text-shiraz">
            @include('site.shared.svg-icons.search')
          </div>
          <span>Site Search</span>
        </div>
      @endif

      <div id="mainSearchForm" class="w-full toggleAction hidden fixed top-0 left-0 bg-white border-b border-black flex justify-center z-50">
        @if($searchPageUrl)
          <form class="w-full max-w-lg py-20" action="{{ $searchPageUrl }}">
            <span class="text-4xl absolute top-0 right-0 mr-5 cursor-pointer toggle-search hover:text-monarch"><i class="fas fa-times"></i></span>
            <div class="flex items-center border-b-4 border-monarch py-2">
              <input class="appearance-none bg-transparent border-none w-full text-gray-700 py-1 px-2 leading-tight focus:outline-none text-5xl" type="text" name="query" placeholder="Search" aria-label="search" required autofocus>
              <button class="flex-shrink-0 px-3 py-3 bg-gray-300 hover:bg-gray-400" type="submit">
                <div class="w-10 h-10 cursor-pointer text-tundora toggle-search">
                  @include('site.shared.svg-icons.search')
                </div>
              </button>
            </div>
            <div class="text-center">
              <span>Press <span class="uppercase">enter</span> to search or <span class="uppercase">esc</span> to close</span>
            </div>
          </form>
        @else
          <div class="w-full max-w-lg py-20">
            <span class="text-center text-4xl absolute top-0 right-0 mr-5 cursor-pointer toggle-search hover:text-monarch"><i class="fas fa-times"></i></span>
            <h2>Search Unavailable</h2>
          </div>
        @endif
      </div>

    </div>

    <div class="main-header__top__search w-full hidden md:block md:w-2/12 xl:w-1/12">
    </div>
  </div>
</section>

<section id="js-navigation-and-comparison-container" class="z-50 bg-white print:hidden">
  @include('layouts.header.navigation.main', [
    'mainNavigation' => $mainNavigation,
  ])
  @include('layouts.header.navigation.full-menu', [
    'searchPageUrl' => $searchPageUrl,
    'mainNavigation' => $mainNavigation,
    'fullMenuNavigation' => $fullMenuNavigation,
    'exclusiveMotorhomePages' => $exclusiveMotorhomePages,
    'otherMotorhomeManufacturerPages' => $otherMotorhomeManufacturerPages,
    'exclusiveCaravanPages' => $exclusiveCaravanPages,
    'otherCaravanManufacturerPages' => $otherCaravanManufacturerPages,
  ])

  <stock-items-comparison-bar
      caravan-comparison-page-url="{{ $caravanComparisonPageUrl }}"
      motorhome-comparison-page-url="{{ $motorhomeComparisonPageUrl }}"
  ></stock-items-comparison-bar>
</section>
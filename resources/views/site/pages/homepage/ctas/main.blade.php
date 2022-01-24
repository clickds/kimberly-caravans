<section class="container mx-auto my-10 px-standard homepage__ctas mobile relative md:hidden">
  <ul class="controls z-40 m-0 p-0 absolute left-0 list-none w-full flex items-center justify-between px-standard container text-regal-blue" aria-label="Carousel Navigation" tabindex="0">
    <li class="prev" data-controls="prev" aria-controls="customize" tabindex="-1">
      <i class="fas fa-angle-left fa-2x"></i>
    </li>
    <li class="next" data-controls="next" aria-controls="customize" tabindex="-1">
      <i class="fas fa-angle-right fa-2x"></i>
    </li>
  </ul>
  <div class="slider-container">
    @if ($dealersPage)
      <div class="cta">
        <a href="{{ $dealersPage->link() }}" class="block">
          <div class="icon-container">
            @include('site.shared.svg-icons.locations')
          </div>
          <p class="text-container">
            <span class="font-bold">{{ $dealersCount }} Branches</span><br/> across the UK
          </p>
        </a>
      </div>
    @endif

    @if ($aboutUsPage)
      <div class="cta">
        <a href="{{ $aboutUsPage->link() }}" class="block">
          <div class="icon-container">
            @include('site.shared.svg-icons.established')
          </div>
          <p class="text-container">
            Over <span class="font-bold">45 Years</span><br/> Experience
          </p>
        </a>
      </div>
    @endif

    @if ($servicesPage)
      <div class="cta">
        <a href="{{ $servicesPage->link() }}" class="block">
          <div class="icon-container">
            @include('site.shared.svg-icons.approved-service-centres')
          </div>
          <p class="text-container">
            NCC <span class="font-bold">Approved</span><br/> Service / Repairs
          </p>
        </a>
      </div>
    @endif

    @if ($specialOffersPage)
      <div class="cta">
        <a href="{{ $specialOffersPage->link() }}" class="block">
          <div class="icon-container">
            @include('site.shared.svg-icons.offers')
          </div>
          <p class="text-container">
            Offers
          </p>
        </a>
      </div>
    @endif

    @if ($accessoriesPage)
      <div class="cta">
        <a href="{{ $accessoriesPage->link() }}" class="block">
          <div class="icon-container">
            @include('site.shared.svg-icons.accessories')
          </div>
          <p class="text-container">
            Accessories
          </p>
        </a>
      </div>
    @endif
  </div>
</section>

<section class="hidden md:flex flex-wrap homepage__ctas container mx-auto my-10 text-center justify-center">
  @if ($dealersPage)
    <div class="w-full sm:w-1/5 my-2 cta">
      <a href="{{ $dealersPage->link() }}" class="block">
        <div class="icon-container">
          @include('site.shared.svg-icons.locations')
        </div>
        <p class="text-container">
          <span class="font-bold">{{ $dealersCount }} Branches</span><br/> across the UK
        </p>
      </a>
    </div>
  @endif

  @if ($aboutUsPage)
    <div class="w-full sm:w-1/5 my-2 cta">
      <a href="{{ $aboutUsPage->link() }}" class="block">
        <div class="icon-container">
          @include('site.shared.svg-icons.established')
        </div>
        <p class="text-container">
          @php
            $now = \Carbon\Carbon::now();
            $foundingDate = \Carbon\Carbon::createFromFormat('Y-m-d', '1973-01-01');
          @endphp
        Over <span class="font-bold">{{ $now->diffInYears($foundingDate) }} Years</span><br/> Experience
        </p>
      </a>
    </div>
  @endif

  @if ($servicesPage)
    <div class="w-full sm:w-1/5 my-2 cta">
      <a href="{{ $servicesPage->link() }}" class="block">
        <div class="icon-container">
          @include('site.shared.svg-icons.approved-service-centres')
        </div>
        <p class="text-container">
          NCC <span class="font-bold">Approved</span><br/> Service / Repairs
        </p>
      </a>
    </div>
  @endif

  @if ($specialOffersPage)
    <div class="w-full sm:w-1/5 my-2 cta">
      <a href="{{ $specialOffersPage->link() }}" class="block">
        <div class="icon-container">
          @include('site.shared.svg-icons.offers')
        </div>
        <p class="text-container">
          Offers
        </p>
      </a>
    </div>
  @endif

  @if ($accessoriesPage)
    <div class="w-full sm:w-1/5 my-2 cta">
      <a href="{{ $accessoriesPage->link() }}" class="block">
        <div class="icon-container">
          @include('site.shared.svg-icons.accessories')
        </div>
        <p class="text-container">
          Accessories
        </p>
      </a>
    </div>
  @endif
</section>
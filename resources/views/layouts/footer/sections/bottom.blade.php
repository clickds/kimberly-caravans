<div id="js-footer-bottom-container" class="bg-regal-blue text-white pt-0 pb-10">
  @if ($currentSite->show_opening_times_and_telephone_number)
    <div id="js-opening-times-container" class="bg-regal-blue z-40">
      <div class="w-full md:w-3/5 lg:w-2/5 mx-auto lg:mx-auto px-standard flex justify-center flex-wrap py-2">
        @if ($currentSite->phone_number)
          <a href="tel: {{ $currentSite->phone_number }}" class="w-auto md:w-2/5 lg:w-1/2 block font-heading text-white flex items-center justify-center">
            <span class="h-6 mr-2 text-web-orange">
              @include('site.shared.svg-icons.phone')
            </span>
            <span class="hidden md:inline lg:text-xl font-semibold uppercase">{{ $currentSite->phone_number }}</span>
          </a>
        @endif
        @if ($locationsPage)
          <a href="{{ $locationsPage->link() }}" class="w-auto md:w-2/5 lg:w-1/2 block font-heading text-white flex items-center justify-center">
            <span class="h-6 mr-2 text-web-orange">
              @include('site.shared.svg-icons.locations')
            </span>
            <span class="hidden md:inline lg:text-xl font-semibold uppercase">Locations</span>
          </a>
        @endif
      </div>
    </div>
  @endif

  @include('layouts.footer.sections._mobile-navigation', [
    'caravanLinks' => $caravanLinks,
    'motorhomeLinks' => $motorhomeLinks,
    'moreNavigation' => $moreNavigation,
  ])
  @include('layouts.footer.sections._tablet-up-navigation', [
    'caravanLinks' => $caravanLinks,
    'motorhomeLinks' => $motorhomeLinks,
    'moreNavigation' => $moreNavigation,
  ])

	<div class="container px-standard mx-auto mt-4 lg:mt-10 flex flex-wrap items-center">
    @if($currentSite->show_footer_content)
      <div class="w-full xl:w-3/5 text-center xl:text-left px-5 xl:px-0">
        <p class="leading-loose text-sm">
          {!! nl2br(e($currentSite->footer_content)) !!}
        </p>
      </div>
    @endif

    @if($currentSite->show_accreditation_icons)
      <div class="w-full xl:w-2/5 mt-5 xl:mt-0 flex justify-center xl:justify-end">
        <a href="https://www.thencc.org.uk/" class="hover:opacity-75" target="_blank" rel="noopener" rel="noreferrer">
          <img src="/images/ncc_logo.png">
        </a>
        @if($preferredDealerPage)
          <a href="{{ $preferredDealerPage->link() }}" class="hover:opacity-75" target="_blank" rel="noopener" rel="noreferrer">
            <img src="/images/campingandcaravanningclubLogo.png" class="ml-5">
          </a>
        @else
          <a href="https://www.campingandcaravanningclub.co.uk/" class="hover:opacity-75" target="_blank" rel="noopener" rel="noreferrer">
            <img src="/images/campingandcaravanningclubLogo.png" class="ml-5">
          </a>
        @endif
      </div>
    @endif

    @if($footerLogos)
      <div class="w-full mt-5 flex justify-center items-center space-x-5 xl:justify-start">
        @foreach($footerLogos as $footerLogo)
          <a href="{{ $footerLogo->linkUrl() }}">
            {{ $footerLogo->image() }}
          </a>
        @endforeach
      </div>
    @endif
	</div>

	<div class="container px-standard mx-auto my-4 lg:my-10 flex items-center justify-center xl:justify-start">
		@if ($legalNavigation->navigationItems->isNotEmpty())
			<ul class="flex flex-wrap justify-center xl:justify-start">
				@foreach ($legalNavigation->navigationItems as $navItem)
					<li class="lg:pr-3 lg:mr-3 w-full text-center lg:text-left lg:w-auto {{ $loop->last ? '' : 'lg:border-r lg:border-white'}}">
            <a href="{{ $navItem->linkUrl() }}" class="text-white text-sm hover:underline">{{ $navItem->name }}</a>
          </li>
				@endforeach
			</ul>
		@endif
	</div>

	<div class="container px-standard mx-auto flex flex-wrap">
		<div class="w-full lg:w-2/4 text-center lg:text-left text-sm">
			&copy; {{ date('Y') }} All Rights Reserved
		</div>

		<div class="w-full lg:w-2/4 text-center lg:text-right mt-4 lg:mt-10 xl:mt-0">
			Site by: <a href="https://www.white-agency.co.uk/" target="_blank" rel="noopener" rel="noreferrer" class="text-sm hover:underline">White Agency</a>
		</div>
	</div>
</div>
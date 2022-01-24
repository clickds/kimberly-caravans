<div class="md:hidden container mx-auto text-center">
  @if ($motorhomeLinks->isNotEmpty())
    <div class="slide-toggle">
      <div>
        <button class="w-full flex flex-col items-center" data-toggle="open">
          <h6 class="px-1 mb-2 text-web-orange font-semibold text-xl">Motorhome</h6>
          <i class="fas fa-chevron-down"></i>
        </button>
        <button class="hidden w-full flex flex-col items-center" data-toggle="close">
          <h6 class="px-1 mb-2 text-web-orange font-semibold text-xl">Motorhome</h6>
          <i class="fas fa-chevron-up"></i>
        </button>
      </div>
      <div class="toggle-content" data-toggle="content">
        <ul class="px-1">
          @foreach ($motorhomeLinks as $link)
            <li class="mb-2">
              <a href="{{ $link->getUrl() }}" class="text-sm hover:underline">{{ $link->getName() }}</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
    <hr class="py-2 my-2">
  @endif


  @if ($caravanLinks->isNotEmpty())
    <div class="slide-toggle">
      <div>
        <button class="w-full flex flex-col items-center" data-toggle="open">
          <h6 class="px-1 mb-2 text-web-orange font-semibold text-xl">Caravan</h6>
          <i class="fas fa-chevron-down"></i>
        </button>
        <button class="hidden w-full flex flex-col items-center" data-toggle="close">
          <h6 class="px-1 mb-2 text-web-orange font-semibold text-xl">Caravan</h6>
          <i class="fas fa-chevron-up"></i>
        </button>
      </div>
      <div class="toggle-content" data-toggle="content">
        <ul class="px-1">
          @foreach ($caravanLinks as $link)
            <li class="mb-2">
              <a href="{{ $link->getUrl() }}" class="text-sm hover:underline">{{ $link->getName() }}</a>
            </li>
          @endforeach
        </ul>
      </div>
      <hr class="py-2 my-2">
    </div>
  @endif

  @if ($moreNavigation->navigationItems->isNotEmpty())
    <div class="slide-toggle">
      <div>
        <button class="w-full flex flex-col items-center" data-toggle="open">
          <h6 class="px-1 mb-2 text-web-orange font-semibold text-xl">More</h6>
          <i class="fas fa-chevron-down"></i>
        </button>
        <button class="hidden w-full flex flex-col items-center" data-toggle="close">
          <h6 class="px-1 mb-2 text-web-orange font-semibold text-xl">More</h6>
          <i class="fas fa-chevron-up"></i>
        </button>
      </div>
      <div class="toggle-content" data-toggle="content">
        <ul class="px-1">
          @foreach($moreNavigation->navigationItems as $navItem)
          <li class="mb-2">
            <a href="{{ $navItem->linkUrl() }}" class="text-sm hover:underline">
              {{ $navItem->name }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>
      <hr class="py-2 my-2">
    </div>
  @endif

  @if($currentSite->show_social_icons)
    <span class="text-xl leading-tight font-heading font-semibold">Follow us on these networks</span>
    <div class="flex text-white justify-center xl:justify-start mt-8 xl:mt-4">
      <a href="https://www.facebook.com/MarquisLeisure" target="_blank" rel="noopener" rel="noreferrer" class="hover:text-shiraz">
        <i class="fab fa-facebook-f text-3xl mr-5"></i>
      </a>
      <a href="https://twitter.com/MarquisLeisure" target="_blank" rel="noopener" rel="noreferrer" class="hover:text-shiraz">
        <i class="fab fa-twitter text-3xl mr-5"></i>
      </a>
      <a href="https://www.instagram.com/marquisleisurehq/?hl=en" target="_blank" rel="noopener" rel="noreferrer" class="hover:text-shiraz">
        <i class="fab fa-instagram text-3xl mr-5"></i>
      </a>
      <a href="https://www.youtube.com/user/MrMarquisMotorhomes" target="_blank" rel="noopener" rel="noreferrer" class="hover:text-shiraz">
        <i class="fab fa-youtube text-3xl"></i>
      </a>
    </div>
  @endif
</div>
<div class="hidden container mx-auto px-standard text-center md:grid gap-5 md:grid-cols-2 lg:grid-cols-4 lg:text-left">
  <div>
    @if ($motorhomeLinks->isNotEmpty())
      <h6 class="px-1 mb-2 mt-4 md:mt-10 text-web-orange font-semibold text-xl">Motorhome</h6>
      <hr class="py-2">
      <ul class="px-1">
        @foreach ($motorhomeLinks as $link)
          <li class="mb-2">
            <a href="{{ $link->getUrl() }}" class="text-sm hover:underline">{{ $link->getName() }}</a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  <div>
    @if ($caravanLinks->isNotEmpty())
      <h6 class="px-1 mb-2 mt-4 md:mt-10 text-web-orange font-semibold text-xl">Caravan</h6>
      <hr class="py-2">
      <ul class="px-1">
        @foreach ($caravanLinks as $link)
          <li class="mb-2">
            <a href="{{ $link->getUrl() }}" class="text-sm hover:underline">{{ $link->getName() }}</a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  <div>
    @if ($moreNavigation->navigationItems->isNotEmpty())
    <h6 class="px-1 mb-2 mt-4 md:mt-10 text-web-orange font-semibold text-xl">More</h6>
    <hr class="py-2">
    <ul class="px-1">
      @foreach($moreNavigation->navigationItems as $navItem)
      <li class="mb-2">
        <a href="{{ $navItem->linkUrl() }}" class="text-sm hover:underline">
          {{ $navItem->name }}
        </a>
      </li>
      @endforeach
    </ul>
    @endif
  </div>

  <div>
    @if($currentSite->show_live_chat)
      <button onclick="olark('api.box.expand')" class="mx-auto lg:mx-0 bg-white flex items-center px-3 text-shiraz font-heading font-bold mt-4 md:mt-10 mb-2 lg:mb-10 text-xl lg:text-2xl rounded-lg focus:outline-none">
        <span>
          <img src="/images/live-chat.svg" class="h-6 mr-2">
        </span>
        <span class="uppercase">Live chat</span>
      </button>

      <hr class="py-2 lg:mt-32">
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
</div>
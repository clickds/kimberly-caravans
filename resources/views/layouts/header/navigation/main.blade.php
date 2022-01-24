<div class="main-header__bottom border-b border-color-white font-heading font-bold shadow-xl">
  <nav role="navigation" aria-label="Main" class="main-navigation container mx-auto flex items-stretch">
    @if ($mainNavigation->navigationItems->isNotEmpty())
      <ul class="hidden flex-wrap text-white xl:text-xl lg:flex lg:w-10/12 xl:w-11/12">
        @foreach ($mainNavigation->navigationItems as $navigationItem)
          <li>
            <a href="{{ $navigationItem->linkUrl() }}" target="{{ $navigationItem->linkTarget() }}" class="{{ $navigationItem->mainNavigationItemDesktopCssClasses() }}">
              {{ $navigationItem->name }}
            </a>
          </li>
        @endforeach
      </ul>
    @endif
    <div class="w-full lg:w-2/12 xl:w-1/12 flex items-center justify-around">
      <div class="lg:hidden w-1/2">
        <a href="/" class="inline-block py-2">
          <img src="/images/logo-white-text.svg" class="w-24 ml-4">
        </a>
      </div>
      <button aria-label="toggle full menu" class="w-1/2 flex items-center justify-end lg:justify-around lg:w-full full-menu-button lg:bg-monarch text-white py-2 hover:bg-web-orange">
        <span class="hidden lg:block lg:w-10/12 xl:w-8/12 flex justify-start lg:justify-center">
          <span class="text-sm uppercase leading-none text-center hidden lg:block">Full Menu</span>
        </span>
        <span class="w-4/12 xl:w-4/12 text-center xl:justify-start flex items-center justify-center">
          <img src="/images/hamburger-menu.svg" class="w-2/5 lg:w-3/5 xl:w-4/5">
        </span>
      </button>
    </div>
  </nav>
</div>
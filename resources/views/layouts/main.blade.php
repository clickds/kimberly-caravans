<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="font-sans">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $pageFacade->getMetaTitle() }}" />
    @if($metaDescription = $pageFacade->getMetaDescription())
      <meta name="description" content="{{ $metaDescription }}">
      <meta property="og:description" content="{{ $metaDescription }}" />
    @endif

    @stack('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @stack('header-scripts')
    <script defer src="{{ mix('js/app.js') }}"></script>

    @if($pageFacade->getSite()->show_live_chat)
      <!-- begin olark code -->
      <script type="text/javascript" async> ;(function(o,l,a,r,k,y){if(o.olark)return; r="script";y=l.createElement(r);r=l.getElementsByTagName(r)[0]; y.async=1;y.src="//"+a;r.parentNode.insertBefore(y,r); y=o.olark=function(){k.s.push(arguments);k.t.push(+new Date)}; y.extend=function(i,j){y("extend",i,j)}; y.identify=function(i){y("identify",k.i=i)}; y.configure=function(i,j){y("configure",i,j);k.c[i]=j}; k=y._={s:[],t:[+new Date],c:{},l:a}; })(window,document,"static.olark.com/jsclient/loader.js");
      olark.identify('6661-208-10-6451');</script>
      <!-- end olark code -->
    @endif
  </head>
  <body>
    <div id="app">
      <header>
        @include('layouts.header.main')
      </header>

      <div>
        @yield('page')
      </div>

      @stack('modals')

      @if($popUp = $pageFacade->firstEligiblePopUp())
        @include('site.shared.popup-for-page', ['popUp' => $pageFacade->firstEligiblePopUp()])
      @endif

      <footer>
        @include('layouts.footer.main')
      </footer>
      @stack('footer-scripts')
    </div>
    @include('cookieConsent::index')
  </body>
</html>
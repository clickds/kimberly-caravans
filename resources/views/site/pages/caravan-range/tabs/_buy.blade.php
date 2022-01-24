@push('header-scripts')
  <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
@endpush

<range-buy-tab
  :models='@json($pageFacade->getFormattedCaravansForVue())'
  site-locale="{{ $site->locale() }}"
  site-currency-code="{{ $site->currencyCode() }}"
  :dealers='@json($pageFacade->getDealers())'
  modal-launcher-size="large"
  book-viewing-icon="{{ view('site.shared.svg-icons.book-viewing')->render() }}"
  make-offer-icon="{{ view('site.shared.svg-icons.make-offer')->render() }}"
  reserve-icon="{{ view('site.shared.svg-icons.reserve')->render() }}"
  recaptcha-site-key="{{ env('RECAPTCHA_SITE_KEY') }}"
  credit-indicator-url="{{ env('BLACK_HORSE_CREDIT_INDICATOR_URL') }}"
  credit-indicator-desktop-image-url="{{ url('/images/blackhorse-desktop.jpg') }}"
  credit-indicator-mobile-image-url="{{ url('/images/blackhorse-mobile.jpg') }}"
>
  @include('site.shared.areas-for-holder', [
    'page' => $pageFacade->getPage(),
    'holder' => 'Buy',
    'areas' => $pageFacade->getAreas('Buy'),
  ])
</range-buy-tab>
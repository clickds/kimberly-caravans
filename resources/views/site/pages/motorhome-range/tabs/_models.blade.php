<range-models-tab
  :models='@json($pageFacade->getFormattedMotorhomesForVue())'
  berth-icon="{{ view('site.shared.svg-icons.berth')->render() }}"
  designated-seats-icon="{{ view('site.shared.svg-icons.seat')->render() }}"
  :dealers='@json($pageFacade->getDealers())'
  modal-launcher-size="small"
  book-viewing-icon="{{ view('site.shared.svg-icons.book-viewing')->render() }}"
  make-offer-icon="{{ view('site.shared.svg-icons.make-offer')->render() }}"
  reserve-icon="{{ view('site.shared.svg-icons.reserve')->render() }}"
  recaptcha-site-key="{{ env('RECAPTCHA_SITE_KEY') }}"
  vehicle-type="motorhome"
>
  @include('site.shared.areas-for-holder', [
    'page' => $pageFacade->getPage(),
    'holder' => 'Models',
    'areas' => $pageFacade->getAreas('Models'),
  ])
</range-models>

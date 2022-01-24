<pop-up
  :id="{{ $popUp->id }}"
  external-url="{{ $popUp->external_url }}"
  page-url="{{ pageLink($popUp->page) }}"
  desktop-image-url="{{ $popUp->getFirstMediaUrl('desktopImage') }}"
  mobile-image-url="{{ $popUp->getFirstMediaUrl('mobileImage') }}"
></pop-up>
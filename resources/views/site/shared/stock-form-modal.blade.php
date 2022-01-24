
@push('header-scripts')
<script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
@endpush

<stock-form-modal-launcher
  size="{{ $size }}"
  book-viewing-icon="{{ view('site.shared.svg-icons.book-viewing')->render() }}"
  make-offer-icon="{{ view('site.shared.svg-icons.make-offer')->render() }}"
  reserve-icon="{{ view('site.shared.svg-icons.reserve')->render() }}"
  item-details="{{ $itemDetails }}"
  :dealers='@json($dealers)'
  recaptcha-site-key="{{ env('RECAPTCHA_SITE_KEY') }}"
></stock-form-modal-launcher>

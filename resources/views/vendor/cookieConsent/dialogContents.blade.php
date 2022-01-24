<div class="js-cookie-consent cookie-consent w-full md:w-1/3 z-max fixed bottom-0 left-0 px-3 py-3 md:pt-0">
  <div class="bg-shiraz text-white p-8 flex flex-col space-y-5 text-xs md:text-sm items-center justify-center">
    @if($cookiePolicyLink)
      <span class="cookie-consent__message">
        We use cookies to help personalise and improve your web experience. By using our website you consent to our use of cookies, some of which may have already been set on your device. View our <a href="{{ $cookiePolicyLink }}" class="underline">Cookie Policy</a> to learn more.
      </span>
    @else
      <span class="cookie-consent__message">
        We use cookies to help personalise and improve your web experience. By using our website you consent to our use of cookies, some of which may have already been set on your device. View our Cookie Policy to learn more.
      </span>
    @endif

    <button class="font-bold whitespace-no-wrap w-full p-3 px-5 bg-white text-shiraz js-cookie-consent-agree cookie-consent__agree">
      Allow cookies
    </button>
  </div>
</div>
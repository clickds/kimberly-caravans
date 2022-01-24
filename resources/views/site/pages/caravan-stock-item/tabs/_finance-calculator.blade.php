<div class="w-full container mx-auto lg:px-12">
  <div class="my-5 w-full">
    <finance-calculator
      item="{{ $stockItem->model }}"
      locale="{{ $site->locale() }}"
      currency-code="{{ $site->currencyCode() }}"
      :initial-price="{{ $stockItem->price ?? $stockItem->recommended_price }}"
      credit-indicator-url="{{ env('BLACK_HORSE_CREDIT_INDICATOR_URL') }}"
      credit-indicator-desktop-image-url="{{ url('/images/blackhorse-desktop.jpg') }}"
      credit-indicator-mobile-image-url="{{ url('/images/blackhorse-mobile.jpg') }}"
    />
  </div>
</div>
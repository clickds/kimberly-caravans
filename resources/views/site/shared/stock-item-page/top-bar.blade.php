<hr class="border-gallery my-2">

<div class="py-2 grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <a href="{{ url()->previous() }}" class="font-heading font-semibold text-lg text-endeavour">
      <i class="fas fa-chevron-left" aria-hidden="true"></i> Back
    </a>
  </div>
  <div class="text-right flex justify-end items-center">
    @if ($comparisonPageExists)
      <compare-button
        :id="{{ $stockItem->id }}"
        stock-type="{{ $stockItem->stockType() }}" button-type="expanded"
        :price="{{ $stockItem->price ?: 'null' }}">
      </compare-button>
    @endif
    <a href="javascript:window.print();" class="ml-2 bg-alabaster flex items-center font-heading font-semibold py-2 px-4 rounded">
      Print Page
      <div class="ml-1 w-4 h-4">
        @include('site.shared.svg-icons.print-page')
      </div>
    </a>
  </div>
</div>

<hr class="border-gallery my-2">
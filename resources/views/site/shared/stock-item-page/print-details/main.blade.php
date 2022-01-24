<div class="w-full hidden print:block print:space-y-5 text-xs">
  @include('site.shared.stock-item-page.print-details._model-details-and-description')
  @include('site.shared.stock-item-page.print-details._image-grid')
  @include('site.shared.stock-item-page.print-details._specifications', ['vehicleType' => $vehicleType])
  @include('site.shared.stock-item-page.print-details._disclaimer')
</div>
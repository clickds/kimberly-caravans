@if ($stockItem->sliderImages()->isNotEmpty())
  @include('site.shared.synced-sliders', [
    'images' => $stockItem->sliderImages(),
  ])
@endif
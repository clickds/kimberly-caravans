<div class="container mx-auto py-5 px-standard">
  <h1 class="py-3 md:py-6 mb-5 text-center text-endeavour">{{ $pageFacade->getRange()->name }} Features</h1>
  @foreach ($pageFacade->getFeatures() as $feature)
    @include('site.shared.vehicle-feature-accordion', [
      'feature' => $feature,
    ])
  @endforeach
</div>
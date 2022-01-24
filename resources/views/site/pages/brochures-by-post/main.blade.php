<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Request Brochures by Post</h1>
    @if ($brochureListingPage = $pageFacade->getBrochureListingPage())
      <p class="mb-5">
        Please tick the brochures you require and fill in your contact details.
      </p>

      <p class="mb-5">
        We will post your brochures as soon as we possibly can.
      </p>

      <p>
        Alternatively if you would prefer to view electronic versions immediately, please download PDF brochures
        <a href="{{ $pageFacade->presentPage($brochureListingPage)->link() }}" class="text-endeavour hover:text-shiraz">here</a>.
      </p>
    @else
      <p class="mb-5">
        Please tick the brochures you require and fill in your contact details.
      </p>

      <p>
        We will post your brochures as soon as we possibly can.
      </p>
    @endif
  </div>
</div>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])
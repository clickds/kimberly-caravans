<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Download Brochures</h1>
    @if ($brochureByPostPage = $pageFacade->getBrochureByPostPage())
      <p class="mb-5">
        All of our brochures are available in PDF format. Simply click on any link to download.
      </p>
      <p>
        Alternatively if you would prefer to receive printed copies, please order brochures for post
        <a href="{{ $pageFacade->presentPage($brochureByPostPage)->link() }}" class="text-endeavour hover:text-shiraz">here</a>.
      </p>
    @else
      <p>
        All of our brochures are available in PDF format. Simply click on any link to download.
      </p>
    @endif
  </div>
</div>

<div class="container mx-auto px-standard py-4">
  @include('site.pages.brochures-listing._collection', [
    'brochureGroups' => $pageFacade->getBrochureGroups(),
  ])
</div>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

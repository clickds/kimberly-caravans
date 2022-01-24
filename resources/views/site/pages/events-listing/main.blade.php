<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Forthcoming Events</h1>
    <p>
      Details of our branch events and where you can see Marquis at the following exhibitions:
    </p>
  </div>
</div>

<hr class="border-gallery">

@include('site.pages.events-listing._collection', [
  'events' => $pageFacade->getEvents(),
  'site' => $pageFacade->getSite(),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])
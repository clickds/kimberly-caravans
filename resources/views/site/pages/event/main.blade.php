<div class="pt-10">
  @include('site.pages.event.event-details', [
    'page' => $pageFacade->getPage(),
    'event' => $pageFacade->getPage()->pageable,
  ])
</div>

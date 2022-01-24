@include('site.shared.listing-pages.top-pagination', [
  'paginator' => $events,
])
<div class="bg-gallery py-4 md:py-10">
  <div class="container mx-auto px-standard">
    @if ($events->isNotEmpty())
      <div class="flex flex-wrap -mx-2">
        @foreach ($events as $event)
          <div class="px-2 mb-2 w-full md:w-1/2 lg:w-1/3">
            @include('site.pages.events-listing._event', [
              'event' => $event,
              'page' => $event->sitePage($site->getWrappedObject()),
              'image' => $event->getImage(),
            ])
          </div>
        @endforeach
      </div>
    @else
        No events to display.
    @endif
  </div>
</div>
@include('site.shared.listing-pages.bottom-pagination', [
  'paginator' => $events,
])
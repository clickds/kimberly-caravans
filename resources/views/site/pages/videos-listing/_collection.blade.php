@include('site.shared.listing-pages.top-pagination', [
  'paginator' => $videos,
])
<div class="bg-gallery py-4 md:py-10">
  <div class="container mx-auto px-standard">
    @if ($videos->isNotEmpty())
      <div class="flex flex-wrap -mx-2">
        @foreach ($videos as $video)
          <div class="px-2 mb-2 w-full md:w-1/2 lg:w-1/3">
            @include('site.pages.videos-listing._video', [
              'video' => $video,
              'page' => $video->sitePage($site->getWrappedObject()),
              'image' => $video->getImage(),
            ])
          </div>
        @endforeach
      </div>
    @else
        No news videos to display.
    @endif
  </div>
</div>
@include('site.shared.listing-pages.bottom-pagination', [
  'paginator' => $videos,
])
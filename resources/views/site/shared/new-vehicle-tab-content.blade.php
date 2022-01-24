<div class="w-full md:w-1/2 lg:w-1/4 px-2 mb-4 min-h-32">
  <a href="{{ $page->link() }}" class="block h-full tabs__content__element">
    @if ($media = $page->getMedia())
      <div class="image-object-fill image-object-center">
        {{ $media('show') }}
      </div>
    @else
      <div class="p-4 text-center">
        {{ $page->linkTitle() }}
      </div>
    @endif
	</a>
</div>
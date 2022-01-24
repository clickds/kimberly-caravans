<article class="h-full review">
  @if($image = $review->image())
    <div class="image-container">
      {{ $image('responsiveIndex') }}
    </div>
  @endif

  <div>
    <div class="mt-6 mb-2 font-bold">
      {{ $review->formattedDate() }}
    </div>

    <a href="{{ $review->linkUrl() }}" target="_blank" rel="noopener" rel="noreferrer" class="mb-2 block text-endeavour underline">
      {{ $review->magazine }}
    </a>

    <h4 class="text-endeavour mb-2">
      {{ $review->title }}
    </h4>

    <p class="mb-2">
      {{ $review->text }}
    </p>

    <a href="{{ $review->linkUrl() }}" target="_blank" rel="noopener" rel="noreferrer" class="block text-endeavour underline">
      Open article
    </a>
  </div>
</article>
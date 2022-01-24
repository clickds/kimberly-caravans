<article class="useful-link h-full">
  <a href="{{ $usefulLink->url }}" class="link" target="_blank" rel="noopener noreferrer">
    <div class="image-container">
      @if ($image = $usefulLink->getImage())
        {{ $image('responsiveIndex') }}
      @endif
    </div>

    <div class="name">
      {{ $usefulLink->name }}
    </div>
  </a>
</article>
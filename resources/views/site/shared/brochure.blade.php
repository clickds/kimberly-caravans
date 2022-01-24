<article>
  @if ($url = $brochure->linkUrl())
    <a href="{{ $url }}" target="_blank" rel="noopener" rel="noreferrer">
      @if ($image = $brochure->getFirstMedia('image'))
        {{ $image }}
      @endif
      <div class="my-4 text-endeavour font-heading font-bold">{{ $brochure->title }}</div>
    </a>
  @else
    @if ($image = $brochure->getFirstMedia('image'))
      {{ $image }}
    @endif
    <div class="my-4 text-endeavour font-heading font-bold">
      {{ $brochure->title }}
    </div>
  @endif
</article>
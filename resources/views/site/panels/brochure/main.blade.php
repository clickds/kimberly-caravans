<div>
  @if ($url = $panel->getBrochure()->linkUrl())
    <a href="{{ $url }}" target="_blank" rel="noopener" rel="noreferrer">
      @if ($image = $panel->getBrochure()->getFirstMedia('image'))
        {{ $image }}
      @endif
      <div class="my-4 text-endeavour font-heading font-bold">{{ $panel->getBrochure()->title }}</div>
    </a>
  @else
    @if ($image = $panel->getBrochure()->getFirstMedia('image'))
      {{ $image }}
    @endif
    <div class="my-4 text-endeavour font-heading font-bold">
      {{ $panel->getBrochure()->title }}
    </div>
  @endif
</div>
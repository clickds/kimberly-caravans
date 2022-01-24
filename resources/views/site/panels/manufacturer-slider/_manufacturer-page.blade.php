<div class="slide">
  <a href="{{ $page->link() }}" class="manufacturer text-center">
    {{ $page->manufacturerName() }}

      <div class="overlay">
        @if ($logo = $page->getLogo())
          {{ $page->getLogo()('manufacturerSlider') }}
        @else
          {{ $page->manufacturerName() }}
        @endif
      </div>
  </a>
</div>
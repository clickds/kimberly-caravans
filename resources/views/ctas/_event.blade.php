<article class="{{ $cta->cssClasses() }}">
  @include('ctas._box-shadow-image', [
    'cta' => $cta,
    'page' => $cta->page(),
  ])

  <div class="hidden md:block">
    <div class="text-tundora font-sans text-lg flex-grow">
      <div>
        {{ $cta->eventDates() }}
      </div>
      <div>
        {{ $cta->eventName() }}
      </div>
    </div>
    @include('ctas._page-link', [
      'linkText' => $cta->link_text,
      'page' => $cta->page(),
    ])
  </div>
</article>
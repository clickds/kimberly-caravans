<article class="{{ $cta->cssClasses() }}">
  <a href="{{ $cta->page()->link() }}" class="flex-grow">
    @include('ctas._image', [
      'cta' => $cta,
    ])
  </a>
</article>
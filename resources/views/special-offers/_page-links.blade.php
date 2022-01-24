<div>
  @foreach ($pages as $specialOfferPage)
    <a href="{{ $specialOfferPage->link() }}" class="button button-shiraz">
      {{ $specialOfferPage->linkText() }}
    </a>
  @endforeach
</div>
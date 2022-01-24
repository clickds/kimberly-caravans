<article class="listing-card">
  @if ($image)
    <div class="image-object-fill image-object-center">
      {{ $image('responsiveIndex') }}
    </div>
  @endif
    <div class="content-container">
      <div class="date">{{ $event->formattedDate() }}</div>
      <h1 class="title">
        <a href="{{ $page->link() }}">
          {{ $event->name }}
        </a>
      </h1>
      <div class="my-1">
        {{ $event->venue() }}
      </div>
      <div class="excerpt">
        {{ $event->summary }}
      </div>
      <a href="{{ $page->link() }}" class="read-more">
        Read more
      </a>
    </div>
</article>
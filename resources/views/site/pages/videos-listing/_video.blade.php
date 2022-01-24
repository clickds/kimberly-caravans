<article class="listing-card">
  @if ($image)
    <a href="{{ $page->link() }}">
      <div class="image-object-fill image-object-center">
        {{ $image('responsiveIndex') }}
      </div>
    </a>
  @endif

  <div class="content-container">
    <div class="date">{{ $video->formattedDate() }}</div>
    <h1 class="title">
      <a href="{{ $page->link() }}">
        {{ $video->title }}
      </a>
    </h1>
    <div class="excerpt">
      {{ $video->limitedExcerpt() }}
    </div>
    <a href="{{ $page->link() }}" class="read-more">
      Read more
    </a>
  </div>
</article>
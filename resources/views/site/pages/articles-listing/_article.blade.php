<article class="listing-card">
  @if ($image)
    <div class="image-object-fill image-object-center">
      {{ $image('responsiveIndex') }}
    </div>
  @endif
    <div class="content-container">
      <div class="date">{{ $article->formattedDate() }}</div>
      <h1 class="title">
        <a href="{{ $page->link() }}">
          {{ $article->title }}
        </a>
      </h1>
      <div class="excerpt">
        {{ $article->excerpt }}
      </div>
      <a href="{{ $page->link() }}" class="read-more">
        Read more
      </a>
    </div>
</article>
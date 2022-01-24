@include('site.shared.listing-pages.top-pagination', [
  'paginator' => $articles,
])

<div class="bg-gallery py-4 md:py-10">
  <div class="container mx-auto px-standard">
    @if ($articles->isNotEmpty())
      <div class="flex flex-wrap -mx-2">
        @foreach ($articles as $article)
          <div class="px-2 mb-2 w-full md:w-1/2 lg:w-1/3">
            @include('site.pages.articles-listing._article', [
              'article' => $article,
              'page' => $article->sitePage($site->getWrappedObject()),
              'image' => $article->getImage(),
            ])
          </div>
        @endforeach
      </div>
    @else
        No news articles to display.
    @endif
  </div>
</div>
@include('site.shared.listing-pages.bottom-pagination', [
  'paginator' => $articles,
])

<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Latest News</h1>
    <p>
      @if ($newsletterSignUpPage = $pageFacade->getNewsletterSignUpPage())
        See below for the latest news across the Marquis Group. If you would like to be kept up to date with this and details of
        any events we are holding simply click <a href="{{ $pageFacade->presentPage($newsletterSignUpPage)->link() }}" class="text-endeavour hover:text-shiraz">here</a>
        and register your email address with us
      @else
        See below for the latest news across the Marquis Group.
      @endif
    </p>
  </div>
</div>

<div>
  <article-filters :categories='@json($pageFacade->getArticleCategories())'
    :dealers='@json($pageFacade->getDealers())'
    :range-names='@json($pageFacade->getRangeNames())'
    />
</div>

@include('site.pages.articles-listing._collection', [
  'articles' => $pageFacade->getArticles(),
  'site' => $pageFacade->getSite(),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Tell Us Your Story</h1>
    <p>
      @if ($sendUsYourStoryPage = $pageFacade->getSendUsYourStoryPage())
        See below for the some of the latest adventures of Marquis customers.
        If you would like to submit a story to share please click <a href="{{ $pageFacade->presentPage($sendUsYourStoryPage)->link() }}" class="text-endeavour hover:text-shiraz">here</a>.
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

@include('site.pages.tell-us-your-story-listing._collection', [
  'articles' => $pageFacade->getArticles(),
  'site' => $pageFacade->getSite(),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

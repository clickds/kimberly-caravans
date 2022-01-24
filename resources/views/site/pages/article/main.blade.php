<div class="container mx-auto px-standard">
	<article>
    @include('site.pages.article._article-content', [
      'article' => $pageFacade->getArticle(),
    ])

    @include('site.shared.areas-for-holder', [
      'page' => $pageFacade->getPage(),
      'holder' => 'Primary',
      'areas' => $pageFacade->getAreas('Primary'),
    ])
	</article>
</div>
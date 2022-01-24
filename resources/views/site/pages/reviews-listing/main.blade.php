<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">See what the Experts say</h1>
    <p class="mb-5">
      For the most comprehensive and independent tests, click and find out what the experts say about the motorhomes & caravans we sell.
    </p>
    <p>
      Browse the reviews for advice and information on new & used motorhomes & caravans from the UK's leading magazines and publications.
    </p>
  </div>
</div>

<div>
  <review-filters
      :categories='@json($pageFacade->getReviewCategories())'
      :dealers='@json($pageFacade->getDealers())'
      :range-names='@json($pageFacade->getRangeNames())'
  />
</div>

@include('site.pages.reviews-listing._collection', [
  'reviews' => $pageFacade->getReviews(),
  'site' => $pageFacade->getSite(),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

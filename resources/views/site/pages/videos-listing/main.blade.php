<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Range, Review & Promotional Videos</h1>
    <p class="mb-5">
      View a range of product promotional videos for our Exclusive ranges including Caravelair Caravans, Benimar Mileo Motorhomes, Benimar Tessoro Motorhomes, Benivan Campervans, Majestic Motorhomes, Mobilvetta Kyacht A-Class Motorhomes, Mobilvetta Kea P Motorhomes, Mobilvetta K-Silver A Class Motorhomes, Randger Campervans and Venus Deluxe Caravans.
    </p>
    <p>
      Also within our videos are a selection of journalist reviews on products we sell.
    </p>
  </div>
</div>

<div>
  <video-filters :categories='@json($pageFacade->getVideoCategories())'
    :dealers='@json($pageFacade->getDealers())'
    :range-names='@json($pageFacade->getRangeNames())'
    />
</div>

@include('site.pages.videos-listing._collection', [
	'videos' => $pageFacade->getVideos(),
	'site' => $pageFacade->getSite(),
])

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

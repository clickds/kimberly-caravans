@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

@include('site.pages.testimonials-listing._collection', [
	'testimonials' => $pageFacade->getTestimonials(),
])

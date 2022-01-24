<range-videos-and-reviews-tab
  range-name="{{ $pageFacade->getRange()->name }}"
  :videos='@json($pageFacade->getFormattedVideosForVue())'
  :reviews='@json($pageFacade->getFormattedReviewsForVue())'
></range-videos-and-reviews-tab>
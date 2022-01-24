<div class="print:hidden">
  @include('layouts.footer.sections.call-to-actions', [
    'ctas' => $ctas,
  ])
  @include('layouts.footer.sections.pages', [
    'tellUsYourStoryPage' => $tellUsYourStoryPage,
    'latestArticle' => $latestArticle,
    'newsListingPage' => $newsListingPage,
    'newsletterSignUpPage' => $newsletterSignUpPage,
    'testimonialsPage' => $testimonialListingPage,
  ])
  @include('layouts.footer.sections.bottom', [
    'currentSite' => $currentSite,
    'currentOpeningTime' => $currentSite->currentOpeningTime(),
    'caravanLinks' => $caravanLinks,
    'legalNavigation' => $legalNavigation,
    'moreNavigation' => $moreNavigation,
    'motorhomeLinks' => $motorhomeLinks,
    'preferredDealerPage' => $preferredDealerPage,
    'locationsPage' => $locationsPage,
  ])
</div>

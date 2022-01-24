@if($pageFacade->getSite()->is_default)
  @include('site.pages.homepage.ctas.main', [
    'specialOffersPage' => $pageFacade->specialOffersPage(),
    'dealersPage' => $pageFacade->dealersPage(),
    'dealersCount' => $pageFacade->dealersCount(),
    'aboutUsPage' => $pageFacade->aboutUsPage(),
    'accessoriesPage' => $pageFacade->accessoriesPage(),
    'servicesPage' => $pageFacade->servicesPage(),
  ])
@endif

@if ($pageFacade->getPage()->exists)
  @include('site.shared.areas-for-holder', [
    'page' => $pageFacade->getPage(),
    'holder' => 'Primary',
    'areas' => $pageFacade->getAreas('Primary'),
  ])
@endif

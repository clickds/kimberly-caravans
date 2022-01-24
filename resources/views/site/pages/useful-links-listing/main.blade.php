<div>
  <div class="container mx-auto px-standard text-center py-5 md:py-10 py-20">
    <h1 class="text-endeavour mb-5">Useful Links</h1>
    <p>
      Here are some useful links we hope may be of interest to you:
    </p>
  </div>
</div>

<div class="container mx-auto px-standard">
  @foreach ($pageFacade->getUsefulLinkCategories() as $usefulLinkCategory)
    <div class="slide-toggle mb-3">
      <div class="bg-endeavour text-white py-2 px-4 flex items-center text-xl">
        <button class="w-full flex items-center space-between" data-toggle="open">
          <h2 class="flex-grow text-white text-left">
            {{ $usefulLinkCategory->name }}
          </h2>
          <i class="fas fa-plus-circle"></i>
        </button>
        <button class="hidden w-full flex items-center space-between" data-toggle="close">
          <h2 class="flex-grow text-white text-left">
            {{ $usefulLinkCategory->name }}
          </h2>
          <i class="fas fa-minus-circle"></i>
        </button>
      </div>
      <div class="toggle-content" data-toggle="content">
        <div class="bg-gallery p-4">
          <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($usefulLinkCategory->usefulLinks as $usefulLink)
              @include('site.pages.useful-links-listing._useful-link', [
                'usefulLink' => $usefulLink,
              ])
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

<div class="bg-gallery mb-5">
  <div class="container mx-auto px-standard py-5 font-heading text-h5">
    @if($pageFacade->hasSelectedPostcode() && $pageFacade->hasGeolocationData())
      <div class="flex flex-row items-center">
        <div class="mr-5">
          Showing dealers closest to postcode: {{ $pageFacade->getSelectedPostcode() }}.
        </div>
        <a href="{{ $pageFacade->getDealerListingPageUrl() }}" class="bg-shiraz py-2 px-10 text-white">Reset</a>
      </div>
    @else
      <div class="flex flex-col lg:flex-row items-center">
        <span class="mr-5 mb-2">Find your nearest Marquis location</span>
        <form class="font-sans">
          <input type="text" name="postcode" value="{{ request()->postcode }}" placeholder="Postcode" class="bg-white p-2 mr-3 w-full md:w-auto mb-2" />
          @if(request()->has('view'))
            <input type="hidden" name="view" value="{{ request()->get('view') }}" />
          @endif
          <input type="submit" value="Search"  class="cursor-pointer bg-shiraz py-2 px-10 text-white w-full md:w-auto" />
        </form>
      </div>

      @if($pageFacade->hasSelectedPostcode() && !$pageFacade->hasGeolocationData())
        <div class="mr-5 font-bold text-lg">Failed to find postcode: {{ $pageFacade->getSelectedPostcode() }}.</div>
      @endif
    @endif
  </div>
</div>
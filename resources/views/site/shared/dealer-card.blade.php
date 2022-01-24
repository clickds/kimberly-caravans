<article>
  <a href="{{ $dealer->getDealerPageUrl() }}" class="cursor-pointer h-full flex flex-col justify-between bg-white">
    <div>
      <div class="w-full bg-endeavour text-white p-4">
        <div class="font-heading text-xl font-medium">{{ $dealer->name }}</div>
        <div>{{ $dealer->getDistanceFromDealerLocationInMiles() }}</div>
      </div>
      <div class="w-full p-4 text-lg font-sans">
        <div class="mb-3">
          {!! nl2br($dealer->getFormattedAddress()) !!}
        </div>
        <div class="text-shiraz">
          @if($phoneNumber = $dealer->getPhoneNumber())
            <div>T: {{ $phoneNumber }}</div>
          @endif
          @if($faxNumber = $dealer->getFaxNumber())
            <div>F: {{ $faxNumber }}</div>
          @endif
        </div>
      </div>
    </div>
    @if($dealer->can_view_motorhomes || $dealer->can_view_caravans)
      <div class="px-4 pb-4">
        <div class="w-full mb-2 grid grid-cols-2 md:w-3/4">
          @if($dealer->can_view_motorhomes)
            @include('site.shared.svg-icons.motorhome')
          @endif
          @if($dealer->can_view_caravans)
            @include('site.shared.svg-icons.caravan')
          @endif
        </div>
        <span>Available to view</span>
      </div>
    @endif
  </a>
</article>
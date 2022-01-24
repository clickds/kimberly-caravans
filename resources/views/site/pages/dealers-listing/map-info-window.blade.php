<div class="w-56">
  <div class="text-white bg-endeavour p-3 font-heading text-lg font-medium">
    <div>{{ $dealer->name }}</div>
    <div class="text-xs">{{ $dealer->getDistanceFromDealerLocationInMiles() }}</div>
  </div>
  <div class="w-full py-4 font-sans">
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

  @if($dealer->can_view_motorhomes || $dealer->can_view_caravans)
    <div class="pb-4">
      <div class="grid mb-2 grid-cols-2 w-3/4">
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

  <a class="underline text-endeavour font-bold text-lg" href="{{ $dealer->getDealerPageUrl() }}">View Branch</a>
</div>
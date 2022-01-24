<div class="flex flex-col mb-10">
  <div class="flex flex-col lg:flex-row">
    <div class="w-full bg-endeavour flex flex-col justify-center items-center lg:w-1/3">
      <div class="wysiwyg p-10 lg:p-20">
        <div class="mb-5">
          <div class="mb-5 text-h4 font-medium">{!! nl2br($dealer->getFormattedAddress) !!}</div>
          <div class="flex flex-col">
            @if($phoneNumber = $dealer->getPhoneNumber())
              <div class="text-h4 font-semibold">T: {{ $phoneNumber }}</div>
            @endif
            @if($faxNumber = $dealer->getFaxNumber())
              <div class="text-h4 font-semibold">F: {{ $faxNumber }}</div>
            @endif
          </div>
        </div>
        @if($websiteUrl = $dealer->getWebsiteUrl())
          <a href="{{ $websiteUrl }}" target="_blank" class="text-h4 font-semibold">{{ $dealer->getWebsiteLinkText() ?? $websiteUrl }}</a>
        @endif
        @if ($dealer->getOpeningHoursContent())
          <div class="w-full wysiwyg bg-endeavour">
            {!! $dealer->getOpeningHoursContent() !!}
          </div>
        @endif
      </div>
    </div>
    <div class="w-full bg-gallery font-heading font-semibold text-h5 p-10 flex-grow">
      <div class="flex flex-row mb-5">
        <a href="{{ $dealer->getGoogleMapsUrl() }}" target="_blank" class="button-endeavour p-3 mr-5">View in Google Maps</a>
        @if($postcode = $dealer->getPostcode())
        <div class="text-white bg-endeavour flex justify-center items-center p-3">Sat Nav: {{ $dealer->getPostcode() }}</div>
        @endif
      </div>

      <google-map :longitude="{{ $dealer->getLongitude() }}" :latitude="{{ $dealer->getLatitude() }}"></google-map>

      <div class="mt-5">
        <div class="mb-5">Door to door route planner, add your postcode below.</div>
        <div>
          <form target="_blank" rel="noopener" rel="noreferrer" action="{{ $dealer->getGoogleMapsDirectionApiUrl() }}">
            <input type="text" name="origin" placeholder="Postcode" class="bg-white p-2 mr-3" />
            <input type="hidden" name="api" value="1" />
            <input type="hidden" name="destination" value="{{ $dealer->getAddressAsSingleLine() }}" />
            <input type="submit" value="Get Directions" class="cursor-pointer bg-shiraz py-2 px-10 text-white" />
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="w-full">
    @if ($videoEmbedCode = $dealer->video_embed_code)
    <div class="plyr-video">
      {!! $videoEmbedCode !!}
    </div>
    @endif
  </div>
</div>
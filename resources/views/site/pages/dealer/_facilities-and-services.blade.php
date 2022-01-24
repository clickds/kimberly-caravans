@if ($dealer->getFacilitiesContent() || $dealer->getServicingCentreContent())
  <div class="container mx-auto py-10 px-standard">
    <div class="w-full lg:w-4/5 mx-auto flex flex-col md:flex-row md:space-x-10 md:justify-center">
      @if ($facilitiesContent = $dealer->getFacilitiesContent())
        <div class="w-full mb-10 md:w-1/2 md:mb-0">
          <h2 class="mb-10 text-endeavour text-h2 font-semibold">Facilities</h2>
          <div class="dealer-servicing-and-facilities-wysiwyg">
            {!! $facilitiesContent !!}
          </div>
        </div>
      @endif
      @if($servicingCentreContent = $dealer->getServicingCentreContent())
        <div class="w-full md:w-1/2">
          <h2 class="mb-10 text-endeavour text-h2 font-semibold">Servicing Centre</h2>
          <div class="dealer-servicing-and-facilities-wysiwyg">
            {!! $servicingCentreContent !!}
          </div>
        </div>
      @endif
    </div>
  </div>
@endif
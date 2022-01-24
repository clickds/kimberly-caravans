<div class="container px-standard mx-auto flex flex-row">
  <div class="w-1/2">
    <div class="text-h3 text-endeavour mb-5">{{ $event->formattedDate() }}</div>
    <h2 class="text-endeavour mb-5">{{ $event->name}}</h2>
    <div class="mb-3">{{ $event->venue() }}</div>
    <div>{!! $event->summary !!}</div>
  </div>
  <div class="w-1/2 image-object-center image-object-cover">
    @if($image = $event->getImage())
      {{ $image('responsiveIndex') }}
    @endif
  </div>
</div>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Primary',
  'areas' => $pageFacade->getAreas('Primary'),
])

<div class="container px-standard mx-auto flex flex-col">
  <div class="w-full my-10">
    <google-map
      :latitude="{{ $event->getLatitude() }}"
      :longitude="{{ $event->getLongitude() }}"
    ></google-map>
  </div>
  <div class="w-full flex flex-col justify-center items-center text-center">
    <div class="text-h2 mb-5 text-endeavour">{{ $event->venue() }}</div>
    <div class="text-h3">{!! nl2br($event->venueAddress()) !!}</div>
  </div>
</div>

@include('site.shared.areas-for-holder', [
  'page' => $pageFacade->getPage(),
  'holder' => 'Secondary',
  'areas' => $pageFacade->getAreas('Secondary'),
])

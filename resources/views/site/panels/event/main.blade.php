<div class="flex flex-col flex-col-reverse md:flex-row">
  <div class="w-full md:w-2/3">
    <div class="mb-2 font-bold">
      {{ $panel->getEvent()->formattedDate() }}
    </div>

    <a href="{{ $panel->linkUrl() }}" target="_blank" rel="noopener" rel="noreferrer" class="mb-2 block text-endeavour underline">
      {{ $panel->getEvent()->name }}
    </a>

    <h4 class="text-endeavour mb-2">
      {{ $panel->getEvent()->venue() }}
    </h4>

    <p class="mb-2">
      {{ $panel->getEvent()->summary }}
    </p>

    <a href="{{ $panel->linkUrl() }}" target="_blank" rel="noopener" rel="noreferrer" class="block text-endeavour underline">
      Read more
    </a>
  </div>

  <div class="w-full mb-10 md:mb-0 md:w-1/3">
    @if($image = $panel->getEvent()->image())
      <div class="image-container">
        {{ $image('responsiveIndex') }}
      </div>
    @endif
  </div>
</div>
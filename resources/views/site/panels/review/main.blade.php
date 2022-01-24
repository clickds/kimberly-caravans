<div class="flex flex-col flex-col-reverse md:flex-row">
  <div class="w-full md:w-2/3">
    <div class="mb-2 font-bold">
      {{ $panel->getReview()->formattedDate() }}
    </div>

    <a href="{{ $panel->getReview()->linkUrl() }}" target="_blank" rel="noopener" rel="noreferrer" class="mb-2 block text-endeavour underline">
      {{ $panel->getReview()->magazine }}
    </a>

    <h4 class="text-endeavour mb-2">
      {{ $panel->getReview()->title }}
    </h4>

    <p class="mb-2">
      {{ $panel->getReview()->text }}
    </p>

    <a href="{{ $panel->getReview()->linkUrl() }}" target="_blank" rel="noopener" rel="noreferrer" class="block text-endeavour underline">
      Open article
    </a>
  </div>

  <div class="w-full mb-10 md:mb-0 md:w-1/3">
    @if($image = $panel->getReview()->image())
      <div class="image-container">
        {{ $image('responsiveIndex') }}
      </div>
    @endif
  </div>
</div>
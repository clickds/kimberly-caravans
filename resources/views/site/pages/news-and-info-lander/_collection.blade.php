@if ($ctas->isNotEmpty())
  <div class="bg-gallery py-4 md:py-8">
    <div class="container mx-auto px-standard">
      <div class="flex flex-wrap -mx-2">
        @foreach ($ctas as $cta)
          <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4 md:mb-8">
            @include($cta->partialPath(), [
              'cta' => $cta,
            ])
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endif
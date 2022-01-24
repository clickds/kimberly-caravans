<div class="bg-white px-standard py-2 md:py-4">
  <div class="-mx-4 flex flex-wrap justify-center">
    @foreach ($ctas as $cta)
      @if ($cta->displayable())
        <div class="px-4 w-1/2 lg:w-1/4 my-2">
          @include($cta->partialPath(), [
            'cta' => $cta,
          ])
        </div>
      @endif
    @endforeach
  </div>
</div>
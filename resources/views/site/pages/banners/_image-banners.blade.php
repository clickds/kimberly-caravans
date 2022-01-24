<div class="image-banner-slider">
  @foreach ($imageBanners as $imageBanner)
    <div class="relative">
      <div class="image-object-cover image-object-center absolute z-10 w-full h-full inset-0">
        {{ $imageBanner->getImage()('responsive') }}
      </div>
      <div class="relative z-20 p-10 h-full container mx-auto px-standard">
        <div class="{{ $imageBanner->contentContainerCssClasses() }}">
          <h1 class="{{ $imageBanner->titleCssClasses() }}">
            @if (!empty($imageBanner->iconPaths()))
              <div class="flex justify-center">
                <div class="{{ $imageBanner->iconCssClasses() }}">
                  @foreach ($imageBanner->iconPaths() as $iconPath)
                    <div class="w-24">
                      @include($iconPath)
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
            {{ $imageBanner->title }}
          </h1>

          @if ($imageBanner->hasContent())
            <div class="{{ $imageBanner->contentCssClasses() }} relative">
              <div class="mix-blend-multiply absolute z-10 w-full h-full">
              </div>
              <div class="relative z-20">
              {!! $imageBanner->content !!}
              </div>
            </div>
          @endif

          @if ($imageBanner->buttons->isNotEmpty())
            <div class="flex flex-wrap -mx-2">
              @foreach($imageBanner->buttons as $button)
                <div class="px-2 w-full lg:w-1/2 mb-2">
                <a href="{{ $button->link() }}" target="{{ $button->linkTarget() }}" class="{{ $button->cssClasses() }}">
                    {{ $button->name }}
                  </a>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>
<div class="w-full md:w-4/5 mx-auto flex items-center">
  <div class="h-8 lg:h-12 self-start {{ $panel->quoteCssColourClass() }}">
    @include('site.shared.svg-icons.quote-marks')
  </div>
  <div class="px-2 text-center flex-grow wysiwyg">
    {!! $panel->getContent() !!}
  </div>
  <div class="h-8 lg:h-12 self-end transform rotate-180 {{ $panel->quoteCssColourClass() }}">
    @include('site.shared.svg-icons.quote-marks')
  </div>
</div>
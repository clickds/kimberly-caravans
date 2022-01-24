@if ($area->getHeading())
  <{{ $area->getHeadingType() }} class="{{ $area->headingCssClasses() }}">
      {{ $area->getHeading() }}
  </{{ $area->getHeadingType() }}>
@endif
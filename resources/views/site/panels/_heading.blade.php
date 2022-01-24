@if ($panel->getHeading())
  <{{ $panel->getHeadingType() }} class="{{ $panel->headingCssClasses() }}">
      {{ $panel->getHeading() }}
  </{{ $panel->getHeadingType() }}>
@endif
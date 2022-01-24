@include('site.panels._heading', [
  'panel' => $panel,
])

<div>
  {!! $panel->getHtmlContent() !!}
</div>
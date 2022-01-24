@include('site.panels._heading', [
  'panel' => $panel,
])

<div class="wysiwyg">
  {!! $panel->getContent() !!}
</div>
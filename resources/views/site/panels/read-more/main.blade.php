@include('site.panels._heading', [
  'panel' => $panel,
])

<div class="wysiwyg">
  {!! $panel->getContent() !!}
</div>

<div id="slide-toggle-{{ $panel->id }}" class="slide-toggle">
  <button class="{{ $panel->toggleButtonCssClasses() }}" data-toggle="open">Read More +</button>
  <div class="wysiwyg toggle-content my-2" data-toggle="content">
    {!! $panel->getReadMoreContent() !!}
    <button class="{{ $panel->toggleButtonCssClasses() }}" data-toggle="close">Read Less -</button>
  </div>
</div>
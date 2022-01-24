@if ($video = $panel->getVideo())
<div class="plyr-video">
  {!! $video->embed_code !!}
</div>
@endif
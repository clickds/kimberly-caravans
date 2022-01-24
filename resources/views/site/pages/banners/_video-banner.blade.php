<video id="player" class="plyr-video-banner">
  @if ($mp4Media)
    <source src="{{ $mp4Media->getUrl() }}" type="video/mp4" />
  @endif
  @if ($webmMedia)
    <source src="{{ $webmMedia->getUrl() }}" type="video/webm" />
  @endif
</video>
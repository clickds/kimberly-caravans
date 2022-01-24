@if ($image = $panel->getImage())
  <div class="image-box-shadow flex items-start justify-center m-4">
    @if($url = $panel->getLinkUrl())
      <a href="{{ $url }}">
        {{ $image('responsive') }}
      </a>
    @else
      {{ $image('responsive') }}
    @endif
  </div>
@endif
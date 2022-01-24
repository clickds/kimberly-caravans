<div class="container mx-auto px-standard">
  <div class="plyr-video">
    {!! $video->embed_code !!}
  </div>
  <div class="mt-4 md:mt-8 font-heading font-medium text-3xl">{{ $video->formattedDate() }}</div>
  <h2 class="text-endeavour">
    {{ $video->title }}
  </h2>
  <div class="excerpt">
    {{ $video->excerpt }}
  </div>
</div>
<article class="banner-container">
@if ($videoBanner)
  @include('site.pages.banners._video-banner', [
    'webmMedia' => $videoBanner->getFirstMedia('webm'),
    'mp4Media' => $videoBanner->getFirstMedia('mp4'),
  ])
@elseif ($imageBanners->isNotEmpty())
  @include('site.pages.banners._image-banners', [
    'imageBanners' => $imageBanners,
  ])
@endif
</article>
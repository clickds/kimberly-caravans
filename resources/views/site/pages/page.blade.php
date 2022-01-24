@extends('layouts.main')

@push('header-scripts')
  <script defer src="{{ mix('js/vue/app.js') }}"></script>
@endpush

@section('title', $pageFacade->getMetaTitle())

@section('page')
<div>
  @include('site.pages._admin-links', [
    'page' => $pageFacade->getPage(),
  ])
  @include('site.pages.banners.main', [
    'videoBanner' => $pageFacade->videoBanner(),
    'imageBanners' => $pageFacade->imageBanners(),
  ])
  @include($pageFacade->templatePath(), [
    'pageFacade' => $pageFacade,
  ])
</div>
@endsection
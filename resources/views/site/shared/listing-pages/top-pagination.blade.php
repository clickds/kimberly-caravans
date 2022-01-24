@if ($paginator->isNotEmpty())
<div class="bg-white py-4">
  @include('site.shared.pagination.per-page-links', [
    'paginator' => $paginator,
  ])

  <div class="container mx-auto px-standard">
    <hr class="bg-gallery my-4" />
  </div>

  {{ $paginator->withQueryString()->appends(['per_page' => request()->get('per_page', 12)])
    ->links('site.shared.pagination.page-links') }}
  </div>
</div>
@endif
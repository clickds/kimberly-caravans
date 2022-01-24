@if ($paginator->isNotEmpty())
  <div class="bg-white py-4">
    {{ $paginator->withQueryString()->appends(['per_page' => request()->get('per_page', 12)])
      ->links('site.shared.pagination.page-links') }}
  </div>
@endif